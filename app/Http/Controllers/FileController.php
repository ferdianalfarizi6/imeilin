<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function serve(\Illuminate\Http\Request $request)
    {
        $path = $request->query('path');

        if (!is_string($path) || !Storage::disk('public')->exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        $fullPath = storage_path('app/public/' . ltrim($path, '/'));

        return response()->file($fullPath);
    }
}
