<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\StaticAnalysis\Rules;

use Nepada\MessageBus\StaticAnalysis\StaticAnalysisFailedException;

final readonly class MethodReturnTypeIsVoidRule
{

    /**
     * @throws StaticAnalysisFailedException
     */
    public function validate(\ReflectionMethod $method): void
    {
        $returnType = $method->getReturnType();

        if ($returnType === null || $returnType->getName() !== 'void') {
            throw StaticAnalysisFailedException::with(
                sprintf('Method "%s" return type must be void', $method->getName()),
                $method->getDeclaringClass()->getName(),
            );
        }
    }

}
