<?php
declare(strict_types = 1);

use Composer\InstalledVersions;
use Composer\Semver\VersionParser;

$config = [];

if (InstalledVersions::satisfies(new VersionParser(), 'symfony/messenger', '<7.1')) {
    $config['parameters']['ignoreErrors'][] = [
        'message' => "#^Method Nepada\\\\MessageBus\\\\Middleware\\\\LoggingMiddleware\\:\\:handle\\(\\) has Symfony\\\\Component\\\\Messenger\\\\Exception\\\\ExceptionInterface in PHPDoc @throws tag but it's not thrown\\.$#",
        'path' => '../../src/MessageBus/Middleware/LoggingMiddleware.php',
        'count' => 1,
    ];
}

return $config;
