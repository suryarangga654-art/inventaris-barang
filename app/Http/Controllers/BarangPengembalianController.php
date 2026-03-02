<?php

namespace App\Http\Controllers;

use App\Models\BarangPengembalian;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class BarangPengembalianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengembalian = BarangPengembalian::with('barang')->get();
        return view('barangpengembalian.index', compact('pengembalian'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barang = Barang::all();
        return view('barangpengembalian.create', compact('barang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {

        $pengembalian = new BarangPengembalian();
        $pengembalian->id_barang = $request->id_barang;
        $pengembalian->nama_pengembali = $request->nama_pengembali;
        $pengembalian->merek = $request->merek;
        $pengembalian->jumlah = $request->jumlah;
        $pengembalian->keterangan = $request->keterangan;
        $pengembalian->tanggal_pengembalian = $request->tanggal_pengembalian;
        $pengembalian->save();

        $barang = Barang::findOrFail($request->id_barang);
        $barang->stock += $request->jumlah;
        $barang->save();
        });

        return redirect()->route('barangpengembalian.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pengembalian = BarangPengembalian::findOrFail($id);
        $barang = Barang::all();
        return view('barangpengembalian.show', compact('pengembalian', 'barang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $barang = Barang::all();
        $pengembalian = BarangPengembalian::findOrFail($id);
        return view('barangpengembalian.edit', compact('barang', 'pengembalian'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::transaction(function () use ($request, $id) {
            $pengembalian = BarangPengembalian::findOrFail($id);
            $barang = Barang::findOrFail($pengembalian->id_barang);

            // LOGIKA UPDATE STOK:
            // Stok Baru = Stok Sekarang - Jumlah Lama + Jumlah Baru
            $barang->stock = ($barang->stock - $pengembalian->jumlah) + $request->jumlah;
            $barang->save();

        $pengembalian = BarangPengembalian::findOrFail($id);
        $pengembalian->id_barang = $request->id_barang;
        $pengembalian->nama_pengembali = $request->nama_pengembali;
        $pengembalian->merek = $request->merek;
        $pengembalian->jumlah = $request->jumlah;
        $pengembalian->keterangan = $request->keterangan;
        $pengembalian->tanggal_pengembalian = $request->tanggal_pengembalian;
        $pengembalian->save();
        });

        return redirect()->route('barangpengembalian.index')->with('success', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       DB::transaction(function () use ($id) {
            $pengembalian = BarangPengembalian::findOrFail($id);
            $barang = Barang::findOrFail($pengembalian->id_barang);

            // Saat data dihapus, stok barang harus dikurangi kembali
            $barang->stock -= $pengembalian->jumlah;
            $barang->save();

            $pengembalian->delete();
        });

        return redirect()->route('barangpengembalian.index')->with('success', 'Data berhasil dihapus');
    }
}
