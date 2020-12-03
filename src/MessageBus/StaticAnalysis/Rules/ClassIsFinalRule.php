<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\StaticAnalysis\Rules;

use Nepada\MessageBus\StaticAnalysis\ReflectionHelper;
use Nepada\MessageBus\StaticAnalysis\StaticAnalysisFailedException;

final class ClassIsFinalRule
{

    /**
     * @param class-string $type
     * @throws StaticAnalysisFailedException
     */
    public function validate(string $type): void
    {
        $reflection = ReflectionHelper::requireClassReflection($type);

        if (! $reflection->isFinal()) {
            throw StaticAnalysisFailedException::with('Class must be final.', $type);
        }
    }

}
