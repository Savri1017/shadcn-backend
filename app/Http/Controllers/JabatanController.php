<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index()
    {
        return response()->json(Jabatan::orderBy('nama_jabatan')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_jabatan' => 'required|string|unique:jabatans,nama_jabatan',
        ]);

        $jabatan = Jabatan::create($data);
        return response()->json($jabatan, 201);
    }
}