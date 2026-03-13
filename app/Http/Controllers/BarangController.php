<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use App\Exports\LaporanExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf; // Import untuk PDF

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barang = Barang::all();
        return view('barang.index', compact('barang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('barang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_barang' => 'required',
            'stock' => 'required|integer',
        ],
        [
            'name_barang.required' => 'Nama barang wajib diisi',
            'stock.required' => 'Stock wajib diisi',
            'stock.integer' => 'Stock harus berupa angka',
        ]);
        $barang = new Barang;
        $barang->name_barang = $request->name_barang;
        $barang->stock = $request->stock;
        $barang->save();
        return redirect()->route('barang.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang.show', compact('barang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang.edit', compact('barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name_barang' => 'required',
            'stock' => 'required|integer',
        ],
        [
            'name_barang.required' => 'Nama barang wajib diisi',
            'stock.required' => 'Stock wajib diisi',
            'stock.integer' => 'Stock harus berupa angka',
        ]);
        $barang = Barang::findOrFail($id);
        $barang->name_barang = $request->name_barang;
        $barang->stock = $request->stock;
        $barang->save();
        return redirect()->route('barang.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Data berhasil dihapus');
    }

    /**
     * METHOD EXPORT EXCEL
     */
public function export() 
    {
        // 2. Gunakan LaporanExport di sini
        return Excel::download(new LaporanExport, 'laporan_barang.xlsx');
    }

    /**
     * METHOD EXPORT PDF
     */
    public function exportPdf(Request $request)
    {
        // Mengambil data barang
        $barangs = Barang::all();
        
        // Data tambahan untuk judul/keterangan di PDF
        $dari = $request->get('dari', 'Semua Data'); 
        $sampai = $request->get('sampai', '-');

        // 3. Memuat view laporan.pdf (Pastikan file ini ada di resources/views/laporan/pdf.blade.php)
        $pdf = Pdf::loadView('laporan.pdf', compact('barangs', 'dari', 'sampai'));
        
        return $pdf->download('laporan-barang.pdf');
    }
}