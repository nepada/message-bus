<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\StaticAnalysis\Rules;

use Nepada\MessageBus\StaticAnalysis\ReflectionHelper;
use Nepada\MessageBus\StaticAnalysis\StaticAnalysisFailedException;

final class ClassHasPublicMethodRule
{

    private string $methodName;

    public function __construct(string $methodName)
    {
        $this->methodName = $methodName;
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
