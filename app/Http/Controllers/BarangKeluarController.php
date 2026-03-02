<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangKeluarController extends Controller
{
    public function index()
    {
        $keluar = BarangKeluar::with('barang')->get();
        return view('barangkeluar.index', compact('keluar'));
    }

    public function create()
    {
        $barang = Barang::all();
        return view('barangkeluar.create', compact('barang'));
    }

    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
          
            $barang = Barang::findOrFail($request->id_barang);

           
            if ($barang->stock < $request->jumlah) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi!');
            }

           
            $keluar = new BarangKeluar();
            $keluar->id_barang = $request->id_barang;
            $keluar->merek = $request->merek;
            $keluar->jumlah = $request->jumlah;
            $keluar->keterangan = $request->keterangan;
            $keluar->tanggal_barang_keluar = $request->tanggal_barang_keluar;
            $keluar->save();

           
            $barang->stock -= $request->jumlah;
            $barang->save();
        });

        return redirect()->route('barangkeluar.index')->with('success', 'Data berhasil disimpan');
    }

    public function show(string $id)
    {
        $keluar = BarangKeluar::findOrFail($id);
        $barang = Barang::all();
        return view('barangkeluar.show', compact('keluar', 'barang'));
    }

    public function edit(string $id)
    {
        $barang = Barang::all();
        $keluar = BarangKeluar::findOrFail($id);
        return view('barangkeluar.edit', compact('keluar', 'barang'));
    }

    public function update(Request $request, string $id)
    {
        DB::transaction(function () use ($request, $id) {
            $keluar = BarangKeluar::findOrFail($id);
            $barang = Barang::findOrFail($keluar->id_barang);

          
            $barang->stock = ($barang->stock + $keluar->jumlah) - $request->jumlah;
            $barang->save();

           
            $keluar->id_barang = $request->id_barang;
            $keluar->merek = $request->merek;
            $keluar->jumlah = $request->jumlah;
            $keluar->keterangan = $request->keterangan;
            $keluar->tanggal_barang_keluar = $request->tanggal_barang_keluar;
            $keluar->save();
        });

        return redirect()->route('barangkeluar.index')->with('success', 'Data berhasil diupdate');
    }

    public function destroy(string $id)
    {
        DB::transaction(function () use ($id) {
            $keluar = BarangKeluar::findOrFail($id);
            $barang = Barang::findOrFail($keluar->id_barang);

        
            $barang->stock += $keluar->jumlah;
            $barang->save();

            $keluar->delete();
        });

        return redirect()->route('barangkeluar.index')->with('success', 'Data berhasil dihapus');
    }
}