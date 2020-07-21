<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\StaticAnalysis\Rules;

use Nepada\MessageBus\StaticAnalysis\ReflectionHelper;
use Nepada\MessageBus\StaticAnalysis\StaticAnalysisFailedException;

final class ShortClassNameMatchesRule
{

    private string $regexPattern;

    public function __construct(string $regexPattern)
    {
        $this->regexPattern = $regexPattern;
    }

    /**
     * @phpstan-param class-string $type
     * @param string $type
     * @throws StaticAnalysisFailedException
     */
    public function validate(string $type): void
    {
        $typeReflection = ReflectionHelper::requireClassReflection($type);

        if (! (bool) preg_match($this->regexPattern, $typeReflection->getShortName(), $matches)) {
            throw StaticAnalysisFailedException::with(
                sprintf(
                    'Class name must match pattern "%s"',
                    $this->regexPattern,
                ),
                $typeReflection->getName(),
            );
        }
    }

}
