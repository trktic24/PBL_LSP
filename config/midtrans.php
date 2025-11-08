<?php

return [
    // Ambil server key dari file .env
    'server_key' => env('MIDTRANS_SERVER_KEY'),

    // Ambil client key dari file .env
    'client_key' => env('MIDTRANS_CLIENT_KEY'),

    // Set ke 'false' untuk mode SANDBOX/Development
    // Set ke 'true' untuk mode PRODUCTION/Asli
    'is_production' => false,

    'is_sanitized' => true,
    'is_3ds' => true,
];