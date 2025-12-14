<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\Api\FrAk07ApiController;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Http\Request;

// Ambil ID Sertifikasi pertama
$sertifikasi = DataSertifikasiAsesi::first();

if (!$sertifikasi) {
    echo "Tidak ada data sertifikasi untuk ditest.\n";
    exit;
}

echo "Testing FR_AK07 API for ID: " . $sertifikasi->id_data_sertifikasi_asesi . "\n";

$controller = new FrAk07ApiController();
$response = $controller->show($sertifikasi->id_data_sertifikasi_asesi);

echo $response->content();
