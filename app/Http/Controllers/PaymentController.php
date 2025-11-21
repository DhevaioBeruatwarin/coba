<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Keranjang;
use App\Models\KaryaSeni;
use App\Models\Transaksi;

class PaymentController extends Controller
{
    public function prepareCheckout(Request $request)
    {
        if (!$request->items || count($request->items) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Pilih produk dahulu'
            ], 400);
        }

        session(['checkout_items' => $request->items]);

        return response()->json([
            'success' => true,
            'redirect' => route('pembeli.checkout.preview')
        ]);
    }

    public function checkoutPreview()
    {
        $pembeli = Auth::guard('pembeli')->user();
        $ids = session('checkout_items', []);

        if (empty($ids)) {
            return redirect()->route('keranjang.index')
                ->with('error', 'Tidak ada produk yang dipilih');
        }

        $produk = Keranjang::with('karya')
            ->whereIn('id_keranjang', $ids)
            ->where('id_pembeli', $pembeli->id_pembeli)
            ->get();

        if ($produk->isEmpty()) {
            return redirect()->route('keranjang.index')
                ->with('error', 'Produk tidak ditemukan');
        }

        $total = 0;
        foreach ($produk as $item) {
            $harga = (int) ($item->karya->harga ?? 0);
            $jumlah = (int) $item->jumlah;
            $total += $harga * $jumlah;
        }

        return view('pembeli.checkout', [
            'pembeli' => $pembeli,
            'produk' => $produk,
            'total' => $total,
            'ids' => $ids
        ]);
    }

    public function bayar(Request $request)
    {
        $pembeli = Auth::guard('pembeli')->user();
        $ids = $request->input('ids', []);

        if (!$ids || count($ids) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada produk yang dipilih'
            ], 400);
        }

        $items = Keranjang::with('karya')
            ->whereIn('id_keranjang', $ids)
            ->where('id_pembeli', $pembeli->id_pembeli)
            ->get();

        if ($items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        $orderId = 'ORDER-' . $pembeli->id_pembeli . '-' . time() . '-' . rand(100, 999);

        $totalAmount = 0;
        $itemDetails = [];
        $transaksiIds = [];

        DB::beginTransaction();
        try {
            foreach ($items as $item) {

                $karya = $item->karya;
                $quantity = (int) $item->jumlah;

                // ========= CEK STOK DISINI =========
                if ($karya->stok < $quantity) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Stok '{$karya->nama_karya}' tidak cukup. Sisa stok: {$karya->stok}"
                    ], 400);
                }

                $namaProduk = $karya->nama_karya ?? "Produk Seni";
                $harga = (int) ($karya->harga ?? 0);

                $subtotal = $harga * $quantity;
                $totalAmount += $subtotal;

                $transaksi = Transaksi::create([
                    'order_id' => $orderId,
                    'id_pembeli' => $pembeli->id_pembeli,
                    'kode_seni' => $item->kode_seni,
                    'tanggal_jual' => now(),
                    'harga' => $subtotal,
                    'jumlah' => $quantity,
                    'status' => 'pending'
                ]);

                $transaksiIds[] = $transaksi->no_transaksi;

                $itemDetails[] = [
                    'id' => $karya->kode_seni ?? 'ITEM-' . rand(100, 999),
                    'price' => $harga,
                    'quantity' => $quantity,
                    'name' => substr($namaProduk, 0, 50)
                ];
            }

            if ($totalAmount <= 0) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Total pembayaran tidak valid.'
                ], 400);
            }

            $customerDetails = [
                'first_name' => $pembeli->nama ?: 'Pembeli',
                'email' => $pembeli->email ?: 'noemail@example.com',
                'phone' => $pembeli->no_hp ?: '0000000000',
            ];

            $midtrans = new \App\Services\MidtransService();
            $snapToken = $midtrans->createTransaction($orderId, (int) $totalAmount, $customerDetails, $itemDetails);

            if (!$snapToken) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Token pembayaran tidak bisa dibuat.'
                ], 500);
            }

            Transaksi::whereIn('no_transaksi', $transaksiIds)->update(['snap_token' => $snapToken]);

            DB::commit();

            session([
                'payment_data' => [
                    'order_id' => $orderId,
                    'snap_token' => $snapToken,
                    'total' => $totalAmount,
                    'cart_ids' => $ids
                ]
            ]);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $orderId
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            if (!empty($transaksiIds)) {
                Transaksi::whereIn('no_transaksi', $transaksiIds)->delete();
            }

            Log::error($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pembayaran.'
            ], 500);
        }
    }

    public function paymentCallback(Request $request)
    {
        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $grossAmount = (int) $request->gross_amount;

        $serverKey = trim(env('MIDTRANS_SERVER_KEY'));
        $signatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transactionStatus = $request->transaction_status;
        $paymentType = $request->payment_type;

        $transaksi = Transaksi::where('order_id', $orderId)->get();

        if ($transaksi->isEmpty()) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $status = 'pending';
        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement')
            $status = 'success';
        if (in_array($transactionStatus, ['deny', 'expire', 'cancel']))
            $status = 'failed';

        $cartIds = session('payment_data.cart_ids', []);

        foreach ($transaksi as $t) {
            $t->status = $status;
            $t->payment_type = $paymentType;

            if ($status === 'success') {
                $t->paid_at = now();

                $karya = KaryaSeni::where('kode_seni', $t->kode_seni)->first();
                if ($karya) {
                    $karya->stok = max(0, $karya->stok - $t->jumlah);
                    $karya->save();
                }
            }

            $t->save();
        }

        if ($status === 'success' && !empty($cartIds)) {
            Keranjang::whereIn('id_keranjang', $cartIds)->delete();
            session()->forget(['checkout_items', 'payment_data']);
        }

        return response()->json(['message' => 'OK']);
    }

    public function paymentSuccess(Request $request)
    {
        $orderId = $request->order_id;

        $transaksi = Transaksi::with('karya')
            ->where('order_id', $orderId)
            ->where('id_pembeli', Auth::guard('pembeli')->id())
            ->get();

        if ($transaksi->isEmpty()) {
            return redirect()->route('pembeli.dashboard')->with('error', 'Transaksi tidak ditemukan');
        }

        $total = $transaksi->sum('harga');

        return view('pembeli.payment-succsess', compact('transaksi', 'orderId', 'total'));
    }

    public function myOrders()
    {
        $pembeli = Auth::guard('pembeli')->user();

        $orders = Transaksi::with('karya')
            ->where('id_pembeli', $pembeli->id_pembeli)
            ->orderBy('tanggal_jual', 'desc')
            ->get();

        return view('pembeli.myorder', compact('orders'));
    }
}
