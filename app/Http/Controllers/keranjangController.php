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

    // public function checkout(Request $request)
    // {
    //     $pembeliId = Auth::guard('pembeli')->id();

    //     if (empty($request->ids)) {
    //         return response()->json(['success' => false, 'message' => 'Pilih produk terlebih dahulu'], 400);
    //     }

    //     $selectedItems = Keranjang::whereIn('id_keranjang', $request->ids)
    //         ->where('id_pembeli', $pembeliId)
    //         ->with('karya')
    //         ->get();

    //     if ($selectedItems->isEmpty()) {
    //         return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
    //     }

    //     foreach ($selectedItems as $item) {
    //         Transaksi::create([
    //             'id_pembeli' => $pembeliId,
    //             'kode_seni' => $item->kode_seni,
    //             'tanggal_jual' => now(),
    //             'harga' => $item->karya->harga * $item->jumlah,
    //             'jumlah' => $item->jumlah
    //         ]);

    //         $item->delete();
    //     }

    //     return response()->json(['success' => true, 'message' => 'Checkout berhasil!']);
    // }

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
        $ids = $request->ids;

        if (empty($ids)) {
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

        // Proses transaksi
        foreach ($items as $item) {
            // Cek stok
            if ($item->karya->stok < $item->jumlah) {
                return redirect()->route('keranjang.index')
                    ->with('error', 'Stok ' . $item->karya->judul . ' tidak mencukupi');
            }

            // Buat transaksi
            Transaksi::create([
                'id_pembeli' => $pembeli->id_pembeli,
                'kode_seni' => $item->kode_seni,
                'tanggal_jual' => now(),
                'harga' => $item->karya->harga * $item->jumlah,
                'jumlah' => $item->jumlah,
                'status' => 'pending' // Opsional: tambahkan status
            ]);

            // Kurangi stok
            $karya = $item->karya;
            $karya->stok -= $item->jumlah;
            $karya->save();

            // Hapus dari keranjang
            $item->delete();
        }

        // Hapus session
        session()->forget('checkout_items');

        return redirect()->route('pembeli.dashboard')
            ->with('success', 'Pembelian berhasil! Terima kasih sudah berbelanja.');
    }

    // ===========================
// HALAMAN KONFIRMASI / CHECKOUT PREVIEW
// // ===========================
//     public function checkoutPreview()
//     {
//         $pembeli = Auth::guard('pembeli')->user();

    //         $ids = session('checkout_items', []);

    //         if (empty($ids)) {
//             return redirect()->route('keranjang.index')
//                 ->with('error', 'Tidak ada produk yang dipilih');
//         }

    //         $produk = Keranjang::with('karya')
//             ->whereIn('id_keranjang', $ids)
//             ->where('id_pembeli', $pembeli->id_pembeli)
//             ->get();

    //         if ($produk->isEmpty()) {
//             return redirect()->route('keranjang.index')
//                 ->with('error', 'Produk tidak ditemukan');
//         }

    //         $total = 0;
//         foreach ($produk as $item) {
//             $total += $item->karya->harga * $item->jumlah;
//         }

    //         return view('pembeli.checkout', [
//             'pembeli' => $pembeli,
//             'produk' => $produk,
//             'total' => $total
//         ]);
//     }

}
