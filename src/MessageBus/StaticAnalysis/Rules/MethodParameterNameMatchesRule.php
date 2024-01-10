<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\StaticAnalysis\Rules;

use Nepada\MessageBus\StaticAnalysis\StaticAnalysisFailedException;
use ReflectionParameter;

final readonly class MethodParameterNameMatchesRule
{

    private string $parameterName;

    public function __construct(string $parameterName)
    {
        $this->parameterName = $parameterName;
    }

    /**
     * @throws StaticAnalysisFailedException
     */
    public function validate(ReflectionParameter $parameter): void
    {
        if ($parameter->getName() !== $this->parameterName) {
            $class = $parameter->getDeclaringClass();
            if ($class === null) {
                throw new \LogicException('Class must be set in this context.');
            }

            throw StaticAnalysisFailedException::with(
                sprintf('Method parameter name must be "%s"', $this->parameterName),
                $class->getName(),
            );
        }
    }

}
