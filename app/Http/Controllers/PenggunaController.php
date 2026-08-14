<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;

class PenggunaController extends Controller
{
    // GET: Ambil semua pengguna
    public function index() {
        return response()->json(Pengguna::all());
    }

    // POST: Tambah pengguna baru
    public function store(Request $request) {
        $data = $request->validate([
            'nama' => 'required|string',
            'email' => 'required|email',
            'peran' => 'required|string'
        ]);
        
        $pengguna = Pengguna::create($data);
        return response()->json($pengguna, 201);
    }

    // PUT: Update pengguna
    public function update(Request $request, $id) {
        $pengguna = Pengguna::findOrFail($id);
        $pengguna->update($request->all());
        return response()->json($pengguna);
    }

    // DELETE: Hapus pengguna
    public function destroy($id) {
        Pengguna::destroy($id);
        return response()->json(['message' => 'Berhasil dihapus']);
    }
}
