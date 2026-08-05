<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$controller = new App\Http\Controllers\FinanceController();
$reflection = new ReflectionClass($controller);
$method = $reflection->getMethod('calculateResults');
$method->setAccessible(true);

$data = [
    'periode' => 'Agustus 2026',
    'pemasukan' => 10000000,
    'kebutuhan_pokok' => 2000000,
    'transportasi' => 500000,
    'cicilan' => 1000000,
    'gaya_hidup' => 500000,
    'tabungan' => 1000000,
    'investasi' => 500000,
    'dana_darurat' => 500000,
    'target_tabungan' => 10000000
];

echo json_encode($method->invoke($controller, $data), JSON_PRETTY_PRINT);
