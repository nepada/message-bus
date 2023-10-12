<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\Logging;

final class PrivateClassPropertiesExtractor
{

    /**
     * @return mixed[]
     */
    public function extract(object $object): array
    {
        $classes = [$object::class];
        $parentReflection = (new \ReflectionClass($object))->getParentClass();
        while ($parentReflection !== false && ! $parentReflection->isInternal()) {
            $classes[] = $parentReflection->getName();
            $parentReflection = $parentReflection->getParentClass();
        }

        $result = [];
        foreach ($classes as $class) {
            // magic :)
            $extract = \Closure::bind(
                fn ($object) => get_object_vars($object),
                null,
                $class,
            );

            $result += $extract($object);
        }

        return $result;
    }

}
