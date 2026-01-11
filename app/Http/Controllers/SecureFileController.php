<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SecureFileController extends Controller
{
    /**
     * Serve a file from the private_docs disk.
     *
     * @param  string  $path
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function show($path)
    {
        // 1. Mandatory Authentication Check
        if (!Auth::check()) {
            abort(403, 'Unauthorized action.');
        }

        // 2. Authorization Logic (TODO: Refine specific policy logic)
        // Example: Only allow if file belongs to user OR user is Admin/Asesor
        $user = Auth::user();
        
        // TODO: Implement specific access control policies here based on $path
        
        // 3. Serve File
        // Ensure strictly reading from private_docs disk
        // Prevent path traversal
        if (strpos($path, '..') !== false) {
             abort(400, 'Invalid path');
        }
        
        // Decode the path in case it was URL-encoded
        $path = urldecode($path);
        
        // Log for debugging (remove in production)
        \Log::info('SecureFile requested', [
            'path' => $path,
            'exists' => Storage::disk('private_docs')->exists($path),
            'user_id' => $user->id_user ?? $user->id,
        ]);
        
        if (!Storage::disk('private_docs')->exists($path)) {
            abort(404);
        }

        $fullPath = Storage::disk('private_docs')->path($path);

        return response()->file($fullPath);
    }
}
