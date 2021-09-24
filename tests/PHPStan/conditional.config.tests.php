<?php
declare(strict_types = 1);

$config = [];

if (! interface_exists(Stringable::class)) {
    // compatbility with PHP <8
    $config['parameters']['ignoreErrors'][] = [
        'message' => '~Parameter \\$message of method NepadaTests\\\\MessageBus\\\\Logging\\\\TestLogger::log\\(\\) has invalid typehint type Stringable~',
        'path' => '../../tests/MessageBus/Logging/TestLogger.php',
        'count' => 1,
    ];
}

return $config;
