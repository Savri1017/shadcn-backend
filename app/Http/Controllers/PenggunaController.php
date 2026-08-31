<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;

class PenggunaController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'total'   => Pengguna::count(),
            'admin'   => Pengguna::where('peran', 'Admin')->count(),
            'manager' => Pengguna::where('peran', 'Manager')->count(),
            'staff'   => Pengguna::where('peran', 'Staff')->count(),
        ];

        $query = Pengguna::query();

        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%")
                  ->orWhere('peran', 'like', "%{$keyword}%");
            });
        }

        $query->orderByRaw("CASE peran WHEN 'Admin' THEN 1 WHEN 'Manager' THEN 2 WHEN 'Staff' THEN 3 ELSE 4 END");

        $perPage = (int) $request->input('per_page', 10);
        $paginated = $query->paginate($perPage);

        return response()->json([
            'data'         => $paginated->items(),
            'current_page' => $paginated->currentPage(),
            'last_page'    => $paginated->lastPage(),
            'per_page'     => $paginated->perPage(),
            'total'        => $paginated->total(),
            'stats'        => $stats,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'  => 'required|string',
            'email' => 'required|email',
            'peran' => 'required|string',
        ]);

        $pengguna = Pengguna::create($data);
        return response()->json($pengguna, 201);
    }

    public function update(Request $request, $id)
    {
        $pengguna = Pengguna::findOrFail($id);
        $pengguna->update($request->all());
        return response()->json($pengguna);
    }

    public function destroy($id)
    {
        Pengguna::destroy($id);
        return response()->json(['message' => 'Berhasil dihapus']);
    }
}