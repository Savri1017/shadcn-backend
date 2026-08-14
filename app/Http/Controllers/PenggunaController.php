<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;

class PenggunaController extends Controller
{
    public function index() {
        return response()->json(Pengguna::all());
    }

    public function store(Request $request) {
        $data = $request->validate([
            'nama' => 'required|string',
            'email' => 'required|email',
            'peran' => 'required|string'
        ]);
        
        $pengguna = Pengguna::create($data);
        return response()->json($pengguna, 201);
    }

    public function update(Request $request, $id) {
        $pengguna = Pengguna::findOrFail($id);
        $pengguna->update($request->all());
        return response()->json($pengguna);
    }

    public function destroy($id) {
        Pengguna::destroy($id);
        return response()->json(['message' => 'Berhasil dihapus']);
    }
}
