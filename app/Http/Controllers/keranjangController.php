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

    public function checkout(Request $request)
    {
        $pembeliId = Auth::guard('pembeli')->id();

        if (empty($request->ids)) {
            return response()->json(['success' => false, 'message' => 'Pilih produk terlebih dahulu'], 400);
        }

        $selectedItems = Keranjang::whereIn('id_keranjang', $request->ids)
            ->where('id_pembeli', $pembeliId)
            ->with('karya')
            ->get();

        if ($selectedItems->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
        }

        foreach ($selectedItems as $item) {
            Transaksi::create([
                'id_pembeli' => $pembeliId,
                'kode_seni' => $item->kode_seni,
                'tanggal_jual' => now(),
                'harga' => $item->karya->harga * $item->jumlah,
                'jumlah' => $item->jumlah
            ]);

            $item->delete();
        }

        return response()->json(['success' => true, 'message' => 'Checkout berhasil!']);
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
}
