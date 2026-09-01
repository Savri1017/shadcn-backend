<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function indexForPengguna(Pengguna $pengguna)
    {
        return response()->json($pengguna->media()->latest()->get());
    }

    public function storeForPengguna(Request $request, Pengguna $pengguna)
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'max:10240', 'mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx'],
            'collection' => ['nullable', 'string', 'max:100'],
            'alt_text' => ['nullable', 'string', 'max:255'],
        ]);

        $file = $validated['file'];
        $collection = $validated['collection'] ?? 'default';
        $path = $file->store('media/pengguna/'.$pengguna->id.'/'.$collection, 'public');

        $media = $pengguna->media()->create([
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
        if ($media->disk === 'public') {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($media->path);
        }

        $media->delete();

        return response()->json(['message' => 'File berhasil dihapus']);
    }
}
