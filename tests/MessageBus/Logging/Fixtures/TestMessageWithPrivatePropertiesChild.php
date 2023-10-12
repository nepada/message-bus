<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\Logging\Fixtures;

class TestMessageWithPrivatePropertiesChild extends TestMessageWithPrivateProperties
{

    private string $privatePropertyChild = 'bar';

    protected function usePrivateProperty(): string
    {
        return $this->privatePropertyChild;
    }

}
