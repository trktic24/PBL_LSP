<?php

$baseUrl = 'http://127.0.0.1:8000/api/ia-01';
$idSertifikasi = 1; // Ganti dengan ID yang valid

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
// Asumsi ID Kriteria 1 dan 2 ada
$data = [
    'hasil' => [
        1 => 'kompeten',
        2 => 'belum_kompeten'
    ],
    'standar_industri' => [
        2 => 'Standar Industri Kurang'
    ],
    'penilaian_lanjut' => [
        2 => 'Perlu observasi ulang'
    ]
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
