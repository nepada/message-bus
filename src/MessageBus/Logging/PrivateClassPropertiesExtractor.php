<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\Logging;

final class PrivateClassPropertiesExtractor
{

    /**
     * @param object $object
     * @return mixed[]
     */
    public function extract(object $object): array
    {
        // magic :)
        $extract = \Closure::bind(
            fn ($object) => get_object_vars($object),
            null,
            $object,
        );

        return $extract($object);
    }

}
