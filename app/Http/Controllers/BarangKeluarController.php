<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\KeluarExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

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
        // 1. Validasi Input (Pastikan tabel 'barangs' sesuai database Anda)
        $request->validate([
            'id_barang' => 'required|exists:barangs,id', 
            'merek'     => 'required',
            'jumlah'    => 'required|integer|min:1',
            'tanggal_barang_keluar' => 'required|date',
        ], [
            'id_barang.required' => 'Barang wajib dipilih',
            'id_barang.exists'   => 'Data barang tidak valid',
            'jumlah.required'    => 'Jumlah wajib diisi',
            'jumlah.min'         => 'Jumlah minimal 1',
        ]);

        // 2. Ambil data barang & Cek apakah stok cukup
        $barang = Barang::findOrFail($request->id_barang);
        
        if ($barang->stock < $request->jumlah) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['jumlah' => "Stok tidak mencukupi! Stok saat ini hanya tersisa: {$barang->stock}"]);
        }

        // 3. Simpan Transaksi
        DB::transaction(function () use ($request, $barang) {
            $keluar = new BarangKeluar();
            $keluar->id_barang = $request->id_barang;
            $keluar->merek = $request->merek;
            $keluar->jumlah = $request->jumlah;
            $keluar->keterangan = $request->keterangan;
            $keluar->tanggal_barang_keluar = $request->tanggal_barang_keluar;
            $keluar->save();

            // Kurangi stok barang
            $barang->decrement('stock', $request->jumlah);
        });

        return redirect()->route('barangkeluar.index')->with('success', 'Data berhasil disimpan');
    }

    public function edit(string $id)
    {
        $barang = Barang::all();
        $keluar = BarangKeluar::findOrFail($id);
        return view('barangkeluar.edit', compact('keluar', 'barang'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'id_barang' => 'required|exists:barangs,id',
            'merek'     => 'required',
            'jumlah'    => 'required|integer|min:1',
            'tanggal_barang_keluar' => 'required|date',
        ]);

        $keluar = BarangKeluar::findOrFail($id);
        $barang = Barang::findOrFail($request->id_barang);

        // LOGIKA CEK STOK SAAT UPDATE:
        // Stok yang tersedia = Stok di gudang sekarang + stok yang sudah keluar (data lama)
        $stokTersedia = $barang->stock + $keluar->jumlah;

        if ($request->jumlah > $stokTersedia) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['jumlah' => "Update gagal! Jumlah melebihi stok. Maksimal yang bisa dikeluarkan: {$stokTersedia}"]);
        }

        DB::transaction(function () use ($request, $keluar, $barang) {
            // Kembalikan stok lama, lalu kurangi dengan jumlah baru
            $barang->stock = ($barang->stock + $keluar->jumlah) - $request->jumlah;
            $barang->save();

            $keluar->update([
                'id_barang' => $request->id_barang,
                'merek'     => $request->merek,
                'jumlah'    => $request->jumlah,
                'keterangan'=> $request->keterangan,
                'tanggal_barang_keluar' => $request->tanggal_barang_keluar,
            ]);
        });

        return redirect()->route('barangkeluar.index')->with('success', 'Data berhasil diupdate');
    }

    public function destroy(string $id)
    {
        DB::transaction(function () use ($id) {
            $keluar = BarangKeluar::findOrFail($id);
            $barang = Barang::findOrFail($keluar->id_barang);

            // Kembalikan stok saat data dihapus
            $barang->increment('stock', $keluar->jumlah);
            $keluar->delete();
        });

        return redirect()->route('barangkeluar.index')->with('success', 'Data berhasil dihapus');
    }

    public function show(string $id)
    {
        $keluar = BarangKeluar::with('barang')->findOrFail($id);
        return view('barangkeluar.show', compact('keluar'));
    }
    public function exportExcel()
{
    return Excel::download(new KeluarExport, 'laporan_barang_keluar.xlsx');
}

public function exportPdf()
{
    $keluar = BarangKeluar::with('barang')->get();
    $pdf = Pdf::loadView('barangkeluar.pdf', compact('keluar'));
    return $pdf->download('laporan_barang_keluar.pdf');
}
}