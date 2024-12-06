<?php
declare(strict_types = 1);

use Composer\InstalledVersions;
use Composer\Semver\VersionParser;

$config = [];

if (InstalledVersions::satisfies(new VersionParser(), 'symfony/messenger', '<7.1')) {
    $config['parameters']['ignoreErrors'][] = [
        'message' => '#^Parameter \\#1 \\$callback of function array_map expects \\(callable\\(array\\<Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\StampInterface\\>\\|Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\StampInterface\\)\\: mixed\\)\\|null, Closure\\(Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\HandledStamp\\)\\: string given\\.$#',
        'path' => '../../tests/MessageBus/Middleware/LoggingMiddlewareTest.phpt',
        'count' => 1,
    ];
}

return $config;
