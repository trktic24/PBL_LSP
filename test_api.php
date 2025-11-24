<?php

$baseUrl = 'http://127.0.0.1:8000/api/fr-ak-07';
$idSertifikasi = 1; // Ganti dengan ID yang valid di database Anda

// 1. Test GET
echo "Testing GET Endpoint...\n";
$ch = curl_init($baseUrl . '/' . $idSertifikasi);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n\n";

if ($httpCode == 200) {
    echo "GET Test Passed!\n";
} else {
    echo "GET Test Failed!\n";
}

// 2. Test POST
echo "Testing POST Endpoint...\n";
$data = [
    'potensi_asesi' => [1, 2], // Sesuaikan dengan ID potensi yang valid
    'penyesuaian' => [
        1 => ['status' => 'Ya', 'keterangan' => [1, 2]], // Sesuaikan ID soal & keterangan
        2 => ['status' => 'Tidak']
    ],
    'acuan_pembanding' => 'Test Acuan',
    'metode_asesmen' => 'Test Metode',
    'instrumen_asesmen' => 'Test Instrumen'
];

$ch = curl_init($baseUrl . '/' . $idSertifikasi);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n\n";

if ($httpCode == 200) {
    echo "POST Test Passed!\n";
} else {
    echo "POST Test Failed!\n";
}
