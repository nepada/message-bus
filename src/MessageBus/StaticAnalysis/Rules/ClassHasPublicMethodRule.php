<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\StaticAnalysis\Rules;

use Nepada\MessageBus\StaticAnalysis\ReflectionHelper;
use Nepada\MessageBus\StaticAnalysis\StaticAnalysisFailedException;

final readonly class ClassHasPublicMethodRule
{

    public function __construct(
        private string $methodName,
    )
    {
    }

    /**
     * @param class-string $type
     * @throws StaticAnalysisFailedException
     */
    public function validate(string $type): void
    {
        $method = ReflectionHelper::requireMethodReflection($type, $this->methodName);

        if (! $method->isPublic()) {
            throw StaticAnalysisFailedException::with(sprintf('Method "%s" is not public', $this->methodName), $type);
        }
    }

}
