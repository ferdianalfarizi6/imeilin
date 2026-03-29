<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function serve(string $folder, string $filename)
    {
        $path = $folder . '/' . $filename;

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        $fullPath = Storage::disk('public')->path($path);
        $mimeType = mime_content_type($fullPath) ?: 'application/octet-stream';
        $file     = Storage::disk('public')->get($path);

        return response($file, 200)->header('Content-Type', $mimeType);
    }
}
