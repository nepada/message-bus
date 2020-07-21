<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\Logging\Fixtures;

class TestLoggableMessage
{

    /**
     * @param mixed[] $properties
     */
    public function __construct(array $properties)
    {
        foreach ($properties as $key => $value) {
            $this->$key = $value;
        }
    }

}
