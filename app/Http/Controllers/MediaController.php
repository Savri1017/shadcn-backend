<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Media;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    private function resolveModel(string $type, int $id)
    {
        $models = [
            'pengguna' => Pengguna::class,
            'jabatan' => Jabatan::class,
        ];

        abort_unless(isset($models[$type]), 422, 'Tipe data tidak didukung.');

        return $models[$type]::findOrFail($id);
    }

    public function index(string $type, int $id, Request $request)
    {
        $model = $this->resolveModel($type, $id);
        $collection = $request->input('collection');

        $query = $model->media()->latest();

        if ($collection) {
            $query->where('collection', $collection);
        }

        return response()->json($query->get());
    }

    public function store(Request $request, string $type, int $id)
    {
        $model = $this->resolveModel($type, $id);

        $data = $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,webp,pdf,doc,docx,xls,xlsx|max:10240',
            'collection' => 'nullable|string|max:100',
            'alt_text' => 'nullable|string|max:255',
            'replace' => 'nullable|boolean',
        ]);

        $collection = $data['collection'] ?? 'default';

        if ($request->boolean('replace')) {
            $oldMedia = $model->media()->where('collection', $collection)->get();

            foreach ($oldMedia as $media) {
                Storage::disk($media->disk)->delete($media->path);
                $media->delete();
            }
        }

        $file = $request->file('file');
        $path = $file->store("media/{$type}/{$id}/{$collection}", 'public');

        $media = $model->media()->create([
            'collection' => $collection,
            'name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'disk' => 'public',
            'path' => $path,
            'size' => $file->getSize(),
            'alt_text' => $data['alt_text'] ?? null,
        ]);

        return response()->json($media, 201);
    }

    public function destroy(Media $media)
    {
        Storage::disk($media->disk)->delete($media->path);
        $media->delete();

        return response()->json(['message' => 'File berhasil dihapus']);
    }
}
