<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Load Composer Autoloader
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel Application
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Arahkan storage folder ke /tmp (karena /tmp bisa ditulis di Vercel)
$app->useStoragePath('/tmp/storage');

// Buat struktur folder sementara di /tmp jika belum ada
$dirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/testing',
    '/tmp/storage/logs',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Jalankan Kernel Laravel
$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);