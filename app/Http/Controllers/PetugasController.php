<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PetugasController extends Controller
{
    // Tampilkan Daftar Petugas
    public function index()
    {
        $users = User::all();
        return view('petugas.index', compact('users'));
    }

    // Simpan Petugas Baru
    public function store(Request $request)
    {
        // 1. Validasi Data
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role'     => 'required|in:admin,petugas',
        ]);

        // 2. Simpan ke Database
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password), // Enkripsi password
            'role'     => $request->role,
        ]);

        // 3. Redirect kembali dengan pesan sukses
        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil ditambahkan!');
    }

    // Hapus Petugas
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Jangan biarkan admin menghapus dirinya sendiri
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();
        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil dihapus!');
    }
}