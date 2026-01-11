<?php

if (!function_exists('getTtdBase64')) {
    /**
     * Convert signature path to base64 encoded data URI.
     * 
     * @param string|null $ttdPath Path to signature file stored in database
     * @param string|null $userId Optional user ID for asesor signature path
     * @param string $type 'asesi' or 'asesor' to determine path structure
     * @return string|null Base64 data URI or null if file not found
     */
    function getTtdBase64(?string $ttdPath, ?string $userId = null, string $type = 'asesi'): ?string
    {
        if (empty($ttdPath)) {
            return null;
        }

        $pathsToTry = [];

        if ($type === 'asesi') {
            // TTD Asesi paths
            $pathsToTry = [
                storage_path('app/private_uploads/ttd_asesi/' . basename($ttdPath)),
                storage_path('app/private_uploads/' . $ttdPath),
            ];
        } elseif ($type === 'asesor') {
            // TTD Asesor paths - with user ID folder structure
            if ($userId) {
                $pathsToTry[] = storage_path('app/private_uploads/asesor_docs/' . $userId . '/' . basename($ttdPath));
            }
            $pathsToTry[] = storage_path('app/private_uploads/asesor_docs/' . basename($ttdPath));
            $pathsToTry[] = storage_path('app/private_uploads/' . $ttdPath);
        } else {
            // Generic path (penyusun/validator/admin)
            $pathsToTry = [
                storage_path('app/private_uploads/' . $ttdPath),
                storage_path('app/public/' . $ttdPath),
            ];
        }

        foreach ($pathsToTry as $path) {
            if (file_exists($path)) {
                $mimeType = getDocMimeType($path);
                $base64 = base64_encode(file_get_contents($path));
                return 'data:' . $mimeType . ';base64,' . $base64;
            }
        }

        return null;
    }
}

if (!function_exists('getDocBase64')) {
    /**
     * Convert document path to base64 encoded string.
     * 
     * @param string|null $docPath Path to document file
     * @return string|null Base64 encoded string or null if file not found
     */
    function getDocBase64(?string $docPath): ?string
    {
        if (empty($docPath)) {
            return null;
        }

        $pathsToTry = [
            storage_path('app/private_uploads/' . $docPath),
            storage_path('app/public/' . $docPath),
            storage_path('app/' . $docPath),
        ];

        // Also try direct path if it looks like a full path
        if (strpos($docPath, '/') !== false) {
            $pathsToTry[] = storage_path('app/private_uploads/' . basename($docPath));
        }

        foreach ($pathsToTry as $path) {
            if (file_exists($path)) {
                $mimeType = getDocMimeType($path);
                $base64 = base64_encode(file_get_contents($path));
                return 'data:' . $mimeType . ';base64,' . $base64;
            }
        }

        return null;
    }
}

if (!function_exists('getDocMimeType')) {
    /**
     * Get MIME type from file extension.
     * 
     * @param string|null $docPath Path to document file
     * @return string MIME type string
     */
    function getDocMimeType(?string $docPath): string
    {
        if (empty($docPath)) {
            return 'application/octet-stream';
        }

        $ext = strtolower(pathinfo($docPath, PATHINFO_EXTENSION));
        
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'pdf' => 'application/pdf',
        ];

        return $mimeTypes[$ext] ?? 'application/octet-stream';
    }
}

