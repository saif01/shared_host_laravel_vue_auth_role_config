<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/api/v1/public/settings?group=branding', 'GET');
$response = $kernel->handle($request);
echo $response->getStatusCode() . PHP_EOL;
echo $response->getContent() . PHP_EOL;
