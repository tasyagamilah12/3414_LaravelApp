<?php

// Forward request ke Vercel Serverless Function
require __DIR__ . '/../vendor/autoload.php';

// Siapkan direktori /tmp untuk storage Laravel di Vercel
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Override path storage & cache ke /tmp agar tidak read-only error
$app->useStoragePath('/tmp/storage');

if (!is_dir('/tmp/storage/framework/views')) {
    mkdir('/tmp/storage/framework/views', 0755, true);
}

if (!is_dir('/tmp/storage/framework/sessions')) {
    mkdir('/tmp/storage/framework/sessions', 0755, true);
}

if (!is_dir('/tmp/storage/framework/cache')) {
    mkdir('/tmp/storage/framework/cache', 0755, true);
}

if (!is_dir('/tmp/storage/logs')) {
    mkdir('/tmp/storage/logs', 0755, true);
}

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);