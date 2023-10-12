<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\Logging;

use Psr\Log\AbstractLogger;

// phpcs:disable Squiz.Commenting.FunctionComment.TypeHintMissing
final class TestLogger extends AbstractLogger
{

    /**
     * @var array<int, array{level: mixed, message: string|\Stringable, context: array<mixed>}>
     */
    public array $records = [];

    /**
     * @var array<string|int, array<int, array{level: mixed, message: string|\Stringable, context: array<mixed>}>>
     */
    public array $recordsByLevel = [];

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     * @param string|\Stringable $message
     * @param array<mixed> $context
     */
    public function log(mixed $level, $message, array $context = []): void
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
