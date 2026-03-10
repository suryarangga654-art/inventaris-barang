<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    public function index()
    {
        $masuk = BarangMasuk::with('barang')->get(); 
        return view('barangmasuk.index', compact('masuk'));
    }

    public function create()
    {
        $barang = Barang::all();
        return view('barangmasuk.create', compact('barang'));
    }

    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
        // $request->validate([
        //     'id_barang' => 'required|exists:barang,id',
        //     'merek' => 'required',
        //     'jumlah' => 'required|integer|min:1',
        //     'keterangan' => 'nullable',
        //     'tanggal_barang_masuk' => 'required|date',
        // ],
        // [
        //     'id_barang.required' => 'Barang wajib dipilih',
        //     'id_barang.exists' => 'Barang tidak valid',
        //     'merek.required' => 'Merek wajib diisi',
        //     'jumlah.required' => 'Jumlah wajib diisi',
        //     'jumlah.integer' => 'Jumlah harus berupa angka',
        //     'jumlah.min' => 'Jumlah harus minimal 1',
        //     'tanggal_barang_masuk.required' => 'Tanggal barang masuk wajib diisi',
        //     'tanggal_barang_masuk.date' => 'Tanggal barang masuk harus berupa tanggal',
        // ]);
            $masuk = new BarangMasuk();
            $masuk->id_barang = $request->id_barang;
            $masuk->merek = $request->merek;
            $masuk->jumlah = $request->jumlah;
            $masuk->keterangan = $request->keterangan;
            $masuk->tanggal_barang_masuk = $request->tanggal_barang_masuk;
            $masuk->save();

            // 2. Update stok di tabel barang (Penambahan)
            $barang = Barang::findOrFail($request->id_barang);
            $barang->stock += $request->jumlah;
            $barang->save();
        });

        return redirect()->route('barangmasuk.index')->with('success', 'Data berhasil disimpan');
    }

    public function show(string $id)
    {
        $masuk = BarangMasuk::findOrFail($id);
        $barang = Barang::all();
        return view('barangmasuk.show', compact('masuk', 'barang'));
    }

    public function edit(string $id)
    {
        $barang = Barang::all();
        $masuk = BarangMasuk::findOrFail($id);
        return view('barangmasuk.edit', compact('barang', 'masuk'));
    }

    public function update(Request $request, string $id)
    {
        DB::transaction(function () use ($request, $id) {
            $masuk = BarangMasuk::findOrFail($id);
            $barang = Barang::findOrFail($masuk->id_barang);

            // LOGIKA UPDATE STOK:
            // Stok Baru = Stok Sekarang - Jumlah Lama + Jumlah Baru
            $barang->stock = ($barang->stock - $masuk->jumlah) + $request->jumlah;
            $barang->save();
        // $request->validate([
        //     'id_barang' => 'required|exists:barang,id',
        //     'merek' => 'required',
        //     'jumlah' => 'required|integer|min:1',
        //     'keterangan' => 'nullable',
        //     'tanggal_barang_masuk' => 'required|date',
        // ],
        // [
        //     'id_barang.required' => 'Barang wajib dipilih',
        //     'id_barang.exists' => 'Barang tidak valid',
        //     'merek.required' => 'Merek wajib diisi',
        //     'jumlah.required' => 'Jumlah wajib diisi',
        //     'jumlah.integer' => 'Jumlah harus berupa angka',
        //     'jumlah.min' => 'Jumlah harus minimal 1',
        //     'tanggal_barang_masuk.required' => 'Tanggal barang masuk wajib diisi',
        //     'tanggal_barang_masuk.date' => 'Tanggal barang masuk harus berupa tanggal',
        // ]);
            // Update data transaksi
            $masuk->id_barang = $request->id_barang;
            $masuk->merek = $request->merek;
            $masuk->jumlah = $request->jumlah;
            $masuk->keterangan = $request->keterangan;
            $masuk->tanggal_barang_masuk = $request->tanggal_barang_masuk;
            $masuk->save();
        });

        return redirect()->route('barangmasuk.index')->with('success', 'Data berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        DB::transaction(function () use ($id) {
            $masuk = BarangMasuk::findOrFail($id);
            $barang = Barang::findOrFail($masuk->id_barang);

            // Saat data dihapus, stok barang harus dikurangi kembali
            $barang->stock -= $masuk->jumlah;
            $barang->save();

            $masuk->delete();
        });

        return redirect()->route('barangmasuk.index')->with('success', 'Data berhasil dihapus');
    }
}