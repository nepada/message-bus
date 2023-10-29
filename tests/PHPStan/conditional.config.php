<?php
declare(strict_types = 1);

$config = [];

if (PHP_VERSION_ID < 8_02_00) {
    $config['parameters']['ignoreErrors'][] = [
        'message' => '#^Call to an undefined method ReflectionClass\\<object\\>\\:\\:isReadOnly\\(\\)\\.$#',
        'path' => '../../src/MessageBus/StaticAnalysis/Rules/ClassIsReadOnlyRule.php',
        'count' => 1,
    ];
}

return $config;
