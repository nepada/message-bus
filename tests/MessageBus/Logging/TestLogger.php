<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\Logging;

use Psr\Log\AbstractLogger;

final class TestLogger extends AbstractLogger
{

    /**
     * @var array<mixed>
     */
    public array $records = [];

    /**
     * @var array<string, array<mixed>>
     */
    public array $recordsByLevel = [];

    /**
     * @param mixed $level
     * @param string|\Stringable $message
     * @param array<mixed> $context
     */
    public function log($level, $message, array $context = []): void
    {
        $record = [
            'level' => $level,
            'message' => $message,
            'context' => $context,
        ];

        $this->recordsByLevel[$record['level']][] = $record;
        $this->records[] = $record;
    }

    public function reset(): void
    {
        $this->records = [];
    }

}
