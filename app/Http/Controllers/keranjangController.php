<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Keranjang;
use App\Models\KaryaSeni;
use App\Models\Transaksi;

class KeranjangController extends Controller
{
    public function index()
    {
        $keranjang = Keranjang::with('karya')
            ->where('id_pembeli', Auth::guard('pembeli')->id())
            ->get();

        return view('pembeli.keranjang', compact('keranjang'));
    }

    public function tambah(Request $request, $kode_seni)
    {
        $pembeli = Auth::guard('pembeli')->user();
        $karya = KaryaSeni::where('kode_seni', $kode_seni)->firstOrFail();

        // Tambah atau update keranjang
        $item = Keranjang::where('id_pembeli', $pembeli->id_pembeli)
            ->where('kode_seni', $kode_seni)
            ->first();

        if ($item) {
            $item->jumlah += 1;
            $item->save();
        } else {
            Keranjang::create([
                'id_pembeli' => $pembeli->id_pembeli,
                'kode_seni' => $kode_seni,
                'jumlah' => 1
            ]);
        }

        // Jika berasal dari tombol "Beli Sekarang"
        if ($request->has('langsung_beli') && $request->langsung_beli == true) {
            return redirect('/pembeli/keranjang')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
        }

        // Kalau biasa, kembalikan respons JSON (untuk animasi)
        $cartCount = Keranjang::where('id_pembeli', $pembeli->id_pembeli)->sum('jumlah');
        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang.',
            'cart_count' => $cartCount
        ]);
    }

    public function hapus($id)
    {
        $item = Keranjang::where('id_keranjang', $id)
            ->where('id_pembeli', Auth::guard('pembeli')->id())
            ->firstOrFail();

        $item->delete();

        return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    public function update(Request $request, $id)
    {
        $item = Keranjang::findOrFail($id);

        // Update jumlah
        $item->jumlah = $request->jumlah;
        $item->save();

        // Hitung ulang total harga untuk item itu
        $newSubtotal = $item->karya->harga * $item->jumlah;

        return response()->json([
            'success' => true,
            'new_subtotal' => $newSubtotal
        ]);
    }

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
        $pembeli = Auth::guard('pembeli')->user();
        $ids = $request->input('ids', []);

        if (!$ids || count($ids) === 0) {
            return redirect()->route('keranjang.index')
                ->with('error', 'Tidak ada produk yang dipilih');
        }

        $items = Keranjang::with('karya')
            ->whereIn('id_keranjang', $ids)
            ->where('id_pembeli', $pembeli->id_pembeli)
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('keranjang.index')
                ->with('error', 'Produk tidak ditemukan');
        }

        foreach ($items as $item) {
            if ($item->karya->stok < $item->jumlah) {
                return redirect()->route('keranjang.index')
                    ->with('error', "Stok {$item->karya->judul} tidak mencukupi");
            }
        }

        // gunakan order_id tanpa unique
        $orderId = 'ORDER-' . $pembeli->id_pembeli . '-' . time();

        $totalAmount = 0;
        $itemDetails = [];
        $transaksiIds = [];

        foreach ($items as $item) {

            $subtotal = $item->karya->harga * $item->jumlah;
            $totalAmount += $subtotal;

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

            $itemDetails[] = [
                'id' => $item->karya->kode_seni,
                'price' => $item->karya->harga,
                'quantity' => $item->jumlah,
                'name' => $item->karya->judul,
            ];
        }

        $customerDetails = [
            'first_name' => $pembeli->nama,
            'email' => $pembeli->email,
            'phone' => $pembeli->no_hp,
        ];

        try {

            $midtrans = new \App\Services\MidtransService();
            $snapToken = $midtrans->createTransaction(
                $orderId,
                $totalAmount,
                $customerDetails,
                $itemDetails
            );

            // simpan snap token
            Transaksi::whereIn('no_transaksi', $transaksiIds)
                ->update(['snap_token' => $snapToken]);

            // simpan session
            session([
                'payment_data' => [
                    'order_id' => $orderId,
                    'snap_token' => $snapToken,
                    'total' => $totalAmount,
                    'cart_ids' => $ids
                ]
            ]);

            return redirect()->route('pembeli.payment.page');

        } catch (\Exception $e) {

            Transaksi::whereIn('no_transaksi', $transaksiIds)->delete();

            return redirect()->route('keranjang.index')
                ->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
        }
    }


    // Callback dari Midtrans
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

        // Update status transaksi
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
            }

            $t->save();
        }

        // Jika sukses, hapus keranjang
        if ($status === 'success') {
            $cartIds = session('payment_data.cart_ids', []);
            Keranjang::whereIn('id_keranjang', $cartIds)->delete();
            session()->forget(['checkout_items', 'payment_data']);
        }

        return response()->json(['message' => 'Transaction status updated']);
    }

    // Halaman sukses pembayaran
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

        return view('pembeli.payment-success', [
            'transaksi' => $transaksi,
            'orderId' => $orderId,
            'total' => $total
        ]);
    }
}