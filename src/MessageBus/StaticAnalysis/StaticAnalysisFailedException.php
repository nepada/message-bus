<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\StaticAnalysis;

class StaticAnalysisFailedException extends \RuntimeException
{

    public static function with(string $problem, string $type): self
    {
        return new self(
            sprintf(
                'Static analysis failed for class "%s": %s',
                $type,
                $problem,
            ),
        );
    }

}
