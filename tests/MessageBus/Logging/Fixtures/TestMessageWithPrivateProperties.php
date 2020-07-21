<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\Logging\Fixtures;

class TestMessageWithPrivateProperties
{

    private string $privateProperty = 'foo';

    protected function usePrivateProperty(): string
    {
        return $this->privateProperty;
    }

}
