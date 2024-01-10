<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\StaticAnalysis\Rules;

use Nepada\MessageBus\StaticAnalysis\ReflectionHelper;
use Nepada\MessageBus\StaticAnalysis\StaticAnalysisFailedException;

final readonly class ClassExistsRule
{

    /**
     * @param class-string $type
     * @throws StaticAnalysisFailedException
     */
    public function validate(string $type): void
    {
        ReflectionHelper::requireClassReflection($type);
    }

}
