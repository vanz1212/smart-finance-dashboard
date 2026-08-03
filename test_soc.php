<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
try {
    \Laravel\Socialite\Facades\Socialite::driver('google')->stateless()->userFromToken('dummy_token');
} catch (\Exception $e) {
    echo 'ERROR_IS: ' . $e->getMessage();
}
?>
