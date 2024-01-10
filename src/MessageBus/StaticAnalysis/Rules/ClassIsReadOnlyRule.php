<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\StaticAnalysis\Rules;

use Nepada\MessageBus\StaticAnalysis\ReflectionHelper;
use Nepada\MessageBus\StaticAnalysis\StaticAnalysisFailedException;

final readonly class ClassIsReadOnlyRule
{

    /**
     * @param class-string $type
     * @throws StaticAnalysisFailedException
     */
    public function validate(string $type): void
    {
        $classReflection = ReflectionHelper::requireClassReflection($type);

        if (! $classReflection->isReadOnly()) {
            throw StaticAnalysisFailedException::with('Class must be readonly', $classReflection->getName());
        }
    }

}
