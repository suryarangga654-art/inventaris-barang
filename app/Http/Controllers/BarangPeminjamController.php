<?php

namespace App\Http\Controllers;

use App\Models\BarangPeminjam;
use App\Models\Barang;
use App\Exports\BarangExport; // Untuk Export Excel
use Maatwebsite\Excel\Facades\Excel; // Untuk Export Excel
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Exports\PeminjamExport;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangPeminjamController extends Controller
{
    public function index()
    {
        $peminjam = BarangPeminjam::with('barang')->get();
        return view('barangpeminjam.index', compact('peminjam'));
    }

    public function create()
    {
        $barang = Barang::all();
        return view('barangpeminjam.create', compact('barang'));
    }

    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            $barang = Barang::findOrFail($request->id_barang);

            if ($barang->stock < $request->jumlah) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi!');
            }

            $peminjam = new BarangPeminjam();
            $peminjam->id_barang = $request->id_barang;
            $peminjam->nama_peminjam = $request->nama_peminjam;
            $peminjam->merek = $request->merek;
            $peminjam->jumlah = $request->jumlah;
            $peminjam->keterangan = $request->keterangan;
            $peminjam->tanggal_peminjaman = $request->tanggal_peminjaman;
            $peminjam->tanggal_pengembalian = $request->tanggal_pengembalian;
            
            // Tambahkan ini agar saat pertama simpan statusnya "dipinjam"
            $peminjam->status = 'dipinjam'; 
            
            $peminjam->save();

            $barang->stock -= $request->jumlah;
            $barang->save();
        });

        return redirect()->route('barangpeminjam.index')->with('success', 'Data berhasil disimpan');
    }

    public function edit(string $id)
    {
        $barang = Barang::all();
        $peminjam = BarangPeminjam::findOrFail($id);
        return view('barangpeminjam.edit', compact('peminjam', 'barang'));
    }

    public function update(Request $request, string $id)
    {
        DB::transaction(function () use ($request, $id) {
            $peminjam = BarangPeminjam::findOrFail($id);
            $barang = Barang::findOrFail($peminjam->id_barang);

            // LOGIKA STOK:
            // Jika status lama "dipinjam" dan status baru "dikembalikan", balikin stok ke gudang
            if ($peminjam->status == 'dipinjam' && $request->status == 'dikembalikan') {
                $barang->stock += $peminjam->jumlah;
            } 
            // Jika status masih dipinjam tapi jumlah berubah (aritmatika lama Anda)
            elseif ($peminjam->status == 'dipinjam' && $request->status == 'dipinjam') {
                $barang->stock = ($barang->stock + $peminjam->jumlah) - $request->jumlah;
            }
            $barang->save();
            // $request->validate([
            //     'id_barang' => 'required|exists:barang,id',
            //     'nama_peminjam' => 'required',
            //     'merek' => 'required',
            //     'jumlah' => 'required|integer|min:1',
            //     'keterangan' => 'nullable',
            //     'tanggal_peminjaman' => 'required|date',
            //     'tanggal_pengembalian' => 'required|date|after_or_equal:tanggal_peminjaman',
            //     'status' => 'required|in:dipinjam,dikembalikan',
            // ],
            // [
            //     'id_barang.required' => 'Barang wajib dipilih',
            //     'id_barang.exists' => 'Barang tidak valid',
            //     'nama_peminjam.required' => 'Nama peminjam wajib diisi',
            //     'merek.required' => 'Merek wajib diisi',
            //     'jumlah.required' => 'Jumlah wajib diisi',
            //     'jumlah.integer' => 'Jumlah harus berupa angka',
            //     'jumlah.min' => 'Jumlah harus minimal 1',
            //     'tanggal_peminjaman.required' => 'Tanggal peminjaman wajib diisi',
            //     'tanggal_peminjaman.date' => 'Tanggal peminjaman harus berupa tanggal',
            //     'tanggal_pengembalian.required' => 'Tanggal pengembalian wajib diisi',
            //     'tanggal_pengembalian.date' => 'Tanggal pengembalian harus berupa tanggal',
            //     'status.required' => 'Status wajib dipilih',
            // ]);
            // UPDATE DATA PEMINJAM
            $peminjam->id_barang = $request->id_barang;
            $peminjam->nama_peminjam = $request->nama_peminjam;
            $peminjam->merek = $request->merek;
            $peminjam->jumlah = $request->jumlah;
            $peminjam->keterangan = $request->keterangan;
            $peminjam->tanggal_peminjaman = $request->tanggal_peminjaman;
            $peminjam->tanggal_pengembalian = $request->tanggal_pengembalian;
            
            // --- INI KUNCINYA AGAR STATUS BERUBAH ---
            $peminjam->status = $request->status; 
            
            $peminjam->save();
        });

        return redirect()->route('barangpeminjam.index')->with('success', 'Data berhasil diupdate');
    }

    public function destroy(string $id)
    {
        DB::transaction(function () use ($id) {
            $peminjam = BarangPeminjam::findOrFail($id);
            $barang = Barang::findOrFail($peminjam->id_barang);

            // Jika dihapus saat masih "dipinjam", stok harus dikembalikan
            if ($peminjam->status == 'dipinjam') {
                $barang->stock += $peminjam->jumlah;
                $barang->save();
            }

            $peminjam->delete();
        });

        return redirect()->route('barangpeminjam.index')->with('success', 'Data berhasil dihapus');
    }

    // Fungsi Export Excel agar tombol tidak "Not Found"
    public function exportExcel()
{
    return Excel::download(new PeminjamExport, 'laporan_peminjaman.xlsx');
}

public function exportPdf()
{
    $peminjam = BarangPeminjam::with('barang')->get();
    $pdf = Pdf::loadView('barangpeminjam.pdf', compact('peminjam'));
    return $pdf->download('laporan_peminjaman.pdf');
}
}