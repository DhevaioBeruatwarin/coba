<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
            $total += $item->karya->harga * $item->jumlah;
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
        try {
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

            // ORDER ID
            $orderId = 'ORDER-' . $pembeli->id_pembeli . '-' . time();

            $totalAmount = 0;
            $itemDetails = [];
            $transaksiIds = [];

            foreach ($items as $item) {

                // FIX: fallback judul jika null
                $namaProduk = $item->karya->judul ?? "Produk Seni";

                // FIX: fallback harga jika null
                $harga = (int) ($item->karya->harga ?? 0);

                // subtotal tetap harga * qty
                $subtotal = $harga * (int) $item->jumlah;
                $totalAmount += $subtotal;

                // Simpan transaksi pending
                $transaksi = Transaksi::create([
                    'order_id' => $orderId,
                    'id_pembeli' => $pembeli->id_pembeli,
                    'kode_seni' => $item->kode_seni,
                    'tanggal_jual' => now(),
                    'harga' => $subtotal,
                    'jumlah' => $item->jumlah,
                    'status' => 'pending'
                ]);

                $transaksiIds[] = $transaksi->no_transaksi;

                // FIX: item_details tidak akan error lagi
                $itemDetails[] = [
                    'id' => $item->karya->kode_seni ?? 'ITEM-' . rand(100, 999),
                    'price' => $harga,
                    'quantity' => (int) $item->jumlah,
                    'name' => substr($namaProduk, 0, 50)
                ];
            }

            // Customer
            $customerDetails = [
                'first_name' => $pembeli->nama ?? "Pembeli",
                'email' => $pembeli->email ?? 'noemail@example.com',
                'phone' => $pembeli->no_hp ?? '0000000000',
            ];

            // Generate Midtrans
            $midtrans = new \App\Services\MidtransService();
            $snapToken = $midtrans->createTransaction(
                $orderId,
                (int) $totalAmount,
                $customerDetails,
                $itemDetails
            );

            // Update transaksi
            Transaksi::whereIn('no_transaksi', $transaksiIds)
                ->update(['snap_token' => $snapToken]);

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

            if (isset($transaksiIds)) {
                Transaksi::whereIn('no_transaksi', $transaksiIds)->delete();
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function paymentCallback(Request $request)
    {
        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $grossAmount = $request->gross_amount;

        // Verifikasi signature
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $signatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transactionStatus = $request->transaction_status;
        $paymentType = $request->payment_type;

        $transaksi = Transaksi::where('order_id', $orderId)->get();

        if ($transaksi->isEmpty()) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $status = 'pending';

        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            $status = 'success';
        } else if ($transactionStatus == 'pending') {
            $status = 'pending';
        } else if ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
            $status = 'failed';
        }

        foreach ($transaksi as $t) {
            $t->status = $status;
            $t->payment_type = $paymentType;

            if ($status === 'success') {
                $t->paid_at = now();

                // Kurangi stok
                $karya = KaryaSeni::where('kode_seni', $t->kode_seni)->first();
                if ($karya) {
                    $karya->stok -= $t->jumlah;
                    $karya->save();
                }
                if (!empty($cartIds)) {
                    Keranjang::whereIn('id_keranjang', $cartIds)->delete();
                }



            }

            $t->save();
        }

        if ($status === 'success') {
            $cartIds = session('payment_data.cart_ids', []);

            if (!empty($cartIds)) {
                Keranjang::whereIn('id_keranjang', $cartIds)->delete();
            }

            // Hapus session
            session()->forget(['checkout_items', 'payment_data']);
        }

        return response()->json(['message' => 'Transaction status updated']);
    }

    public function paymentSuccess(Request $request)
    {
        $orderId = $request->order_id;

        $transaksi = Transaksi::with('karya')
            ->where('order_id', $orderId)
            ->where('id_pembeli', Auth::guard('pembeli')->id())
            ->get();

        if ($transaksi->isEmpty()) {
            return redirect()->route('pembeli.dashboard')
                ->with('error', 'Transaksi tidak ditemukan');
        }

        $total = $transaksi->sum('harga');

        return view('pembeli.payment-succsess', [
            'transaksi' => $transaksi,
            'orderId' => $orderId,
            'total' => $total
        ]);
    }

    // Tambahkan method ini di PaymentController.php atau buat OrderController baru

    public function myOrders()
    {
        $pembeli = Auth::guard('pembeli')->user();

        // Ambil semua transaksi pembeli, diurutkan dari yang terbaru
        $orders = Transaksi::with('karya')
            ->where('id_pembeli', $pembeli->id_pembeli)
            ->orderBy('tanggal_jual', 'desc')
            ->get();

        return view('pembeli.myorder', [
            'orders' => $orders
        ]);
    }
}