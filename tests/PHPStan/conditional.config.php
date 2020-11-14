<?php
declare(strict_types = 1);

$config = [];

if (PHP_VERSION_ID >= 8_00_00) {
    // Change of signature in PHP 8.0
    $config['parameters']['ignoreErrors'][] = [
        'message' => '~Strict comparison using === between array and false will always evaluate to false~',
        'path' => '../../src/MessageBus/Logging/MessageContextResolver.php',
        'count' => 1,
    ];
}

return $config;
