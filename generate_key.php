<?php
$key = 'base64:' . base64_encode(random_bytes(32));
$envFile = '.env';
$envContent = file_get_contents($envFile);
$envContent = preg_replace('/APP_KEY=.*/', 'APP_KEY=' . $key, $envContent);
file_put_contents($envFile, $envContent);
echo "APP_KEY set to: " . $key . "\n";
