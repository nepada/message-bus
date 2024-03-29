<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\StaticAnalysis;

final readonly class ReflectionHelper
{

    /**
     * @template T of object
     * @param class-string<T> $type
     * @return \ReflectionClass<T>
     * @throws StaticAnalysisFailedException
     */
    public static function requireClassReflection(string $type): \ReflectionClass
    {
        try {
            return new \ReflectionClass($type);

        } catch (\ReflectionException $exception) {
            throw StaticAnalysisFailedException::with('Class does not exist', $type);
        }
    }

    /**
     * @template T of object
     * @param class-string<T> $class
     * @throws StaticAnalysisFailedException
     */
    public static function requireMethodReflection(string $class, string $methodName): \ReflectionMethod
    {
        try {
            return self::requireClassReflection($class)->getMethod($methodName);

        } catch (\ReflectionException $exception) {
            throw StaticAnalysisFailedException::with(sprintf('Method "%s" does not exist', $methodName), $class);
        }
    }

}
