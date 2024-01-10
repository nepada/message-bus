<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\StaticAnalysis\Rules;

use Nepada\MessageBus\StaticAnalysis\ReflectionHelper;
use Nepada\MessageBus\StaticAnalysis\StaticAnalysisFailedException;

final readonly class ClassNameHasSuffixRule
{

    private string $suffix;

    public function __construct(string $suffix)
    {
        $this->suffix = $suffix;
    }

    /**
     * @param class-string $type
     * @throws StaticAnalysisFailedException
     */
    public function validate(string $type): void
    {
        $typeReflection = ReflectionHelper::requireClassReflection($type);

        if (! (bool) preg_match($this->getRegexPattern(), $typeReflection->getShortName(), $matches)) {
            throw StaticAnalysisFailedException::with(
                sprintf(
                    'Class must have suffix "%s"',
                    $this->suffix,
                ),
                $typeReflection->getName(),
            );
        }
    }

    public function getRegexPattern(): string
    {
        return sprintf('#^(.+)%s$#', $this->suffix);
    }

}
