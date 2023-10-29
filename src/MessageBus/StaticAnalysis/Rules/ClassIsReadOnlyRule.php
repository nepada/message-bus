<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\StaticAnalysis\Rules;

use Nepada\MessageBus\StaticAnalysis\ReflectionHelper;
use Nepada\MessageBus\StaticAnalysis\StaticAnalysisFailedException;

final class ClassIsReadOnlyRule
{

    /**
     * @param class-string $type
     * @throws StaticAnalysisFailedException
     */
    public function validate(string $type): void
    {
        $classReflection = ReflectionHelper::requireClassReflection($type);

        $checkedClassReflection = $classReflection;
        while ($checkedClassReflection !== false) {
            if ($classReflection->getStaticProperties() !== []) {
                throw StaticAnalysisFailedException::with('Readonly class cannot have static properties', $classReflection->getName());
            }

            foreach ($classReflection->getProperties() as $property) {
                if (! $property->isReadOnly()) {
                    throw StaticAnalysisFailedException::with(sprintf('Property %s must be readonly', $property->getName()), $classReflection->getName());
                }
            }

            $checkedClassReflection = $checkedClassReflection->getParentClass();
        }

        if (PHP_VERSION_ID < 8_02_00) {
            return;
        }

        if (! $classReflection->isReadOnly()) {
            $exception = StaticAnalysisFailedException::with('Class must be readonly', $classReflection->getName());
            trigger_error($exception->getMessage(), E_USER_DEPRECATED);
        }
    }

}
