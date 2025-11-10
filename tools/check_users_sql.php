<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
\Illuminate\Foundation\Bootstrap\BootProviders::boot($app);
\Illuminate\Foundation\Bootstrap\LoadConfiguration::bootstrap($app);

// Initialize DB
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pdo = \DB::getPdo();
$res = \DB::select("SELECT sql FROM sqlite_master WHERE name = 'users'");
if (count($res)) {
    echo $res[0]->sql . PHP_EOL;
} else {
    echo "no users table" . PHP_EOL;
}
