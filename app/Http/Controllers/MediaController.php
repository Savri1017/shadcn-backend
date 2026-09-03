<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Media;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index(Request $request, string $type, int $id)
    {
        $model = $this->resolveModel($type, $id);

        $media = $model->media()
            ->when(
                $request->filled('collection'),
                fn ($query) => $query->where('collection', $request->query('collection'))
            )
            ->latest()
            ->get();

        return response()->json($media);
    }

    public function store(Request $request, string $type, int $id)
    {
        $model = $this->resolveModel($type, $id);
        $collection = $request->input('collection', 'default');
        
        $mimeRule = $collection === 'avatar'
            ? 'mimes:jpg,jpeg,png,webp'
            : 'mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx';

        $validated = $request->validate([
            'file' => ['required', 'file', 'max:10240', $mimeRule],
            'collection' => ['nullable', 'string', 'max:100'],
            'alt_text' => ['nullable', 'string', 'max:255'],
        ]);

        $file = $validated['file'];
        $collection = $validated['collection'] ?? 'default';
        $path = $file->store('media/'.$type.'/'.$model->getKey().'/'.$collection, 'public');

        $media = $model->media()->create([
            'collection' => $collection,
            'name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'disk' => 'public',
            'path' => $path,
            'size' => $file->getSize(),
            'alt_text' => $validated['alt_text'] ?? null,
        ]);

        return response()->json($media, 201);
    }

    public function destroy(Media $media)
    {
        Storage::disk($media->disk)->delete($media->path);
        $media->delete();

        return response()->json(['message' => 'File berhasil dihapus']);
    }

    private function resolveModel(string $type, int $id)
    {
        return match ($type) {
            'pengguna' => Pengguna::findOrFail($id),
            'jabatan' => Jabatan::findOrFail($id),
            default => abort(404, 'Tipe media tidak didukung.'),
        };
    }
}
