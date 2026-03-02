<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use App\Exports\BarangExport;
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
        return Excel::download(new BarangExport, 'data_barang.xlsx');
    }

    /**
     * METHOD EXPORT PDF
     */
    public function exportPdf()
    {
        $barang = Barang::all();
        $pdf = Pdf::loadView('barang.pdf', compact('barang'));
        return $pdf->download('data_barang.pdf');
    }
}