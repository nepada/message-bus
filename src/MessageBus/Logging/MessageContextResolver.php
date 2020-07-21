<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\Logging;

use Nepada\MessageBus\StaticAnalysis\MessageType;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class MessageContextResolver
{

    private string $keyPrefix;

    private PrivateClassPropertiesExtractor $privateClassPropertiesExtractor;

    public function __construct(
        PrivateClassPropertiesExtractor $privateClassPropertiesExtractor,
        string $keyPrefix = ''
    )
    {
        $this->privateClassPropertiesExtractor = $privateClassPropertiesExtractor;
        $this->keyPrefix = $keyPrefix;
    }

    /**
     * @param Envelope $envelope
     * @param \Throwable|null $exception
     * @return mixed[]
     */
    public function getContext(Envelope $envelope, ?\Throwable $exception = null): array
    {
        if ($exception instanceof HandlerFailedException) {
            $envelope = $exception->getEnvelope();
            $nestedExceptions = $exception->getNestedExceptions();
        } elseif ($exception !== null) {
            $nestedExceptions = [$exception];
        }

        $context = array_merge($this->getEnvelopeContext($envelope), $this->getExceptionContext($nestedExceptions ?? []));

        $context = $this->mergeSafely($context, $this->privateClassPropertiesExtractor->extract($envelope->getMessage()));

        if ($this->keyPrefix !== '') {
            $context = $this->prefixArrayKeys($context, $this->keyPrefix);
        }

        return $context;
    }

    /**
     * @param Envelope $envelope
     * @return mixed[]
     */
    private function getEnvelopeContext(Envelope $envelope): array
    {
        $context = [];
        $context['messageType'] = MessageType::fromMessage($envelope->getMessage())->toString();

        $counter = 1;
        /** @var HandledStamp $handledStamp */
        foreach ($envelope->all(HandledStamp::class) as $handledStamp) {
            $suffix = $counter === 1 ? '' : "_$counter";
            $context["handlerType{$suffix}"] = explode('::', $handledStamp->getHandlerName(), 2)[0];
            $counter++;
        }

        return $context;
    }

    /**
     * @param \Throwable[] $nestedExceptions
     * @return mixed[]
     */
    private function getExceptionContext(array $nestedExceptions): array
    {
        $context = [];
        $counter = 1;
        foreach ($nestedExceptions as $nestedException) {
            $suffix = $counter === 1 ? '' : "_$counter";
            $context["exceptionType{$suffix}"] = get_class($nestedException);
            $context["exceptionMessage{$suffix}"] = $nestedException->getMessage();
            $counter++;
        }
        return $context;
    }

    /**
     * @param mixed[] $destination
     * @param mixed[] $source
     * @return mixed[]
     */
    private function mergeSafely(array $destination, array $source): array
    {
        $sourceItemsWithAmbiguousKeys = array_intersect_key($source, $destination);

        if ($sourceItemsWithAmbiguousKeys !== []) {
            trigger_error(
                sprintf(
                    'Message context merge failed with following duplicate keys: "%s"',
                    implode(', ', array_keys($sourceItemsWithAmbiguousKeys)),
                ),
                E_USER_WARNING,
            );

            $sourceItemsWithDisambiguatedKeys = $this->prefixArrayKeys($sourceItemsWithAmbiguousKeys, 'disambiguated_');
            $result = $this->mergeSafely($destination, $sourceItemsWithDisambiguatedKeys);

            $sourceItemsWithNotAmbiguousKeys = array_diff_key($source, $sourceItemsWithAmbiguousKeys);
            $result = array_merge($result, $sourceItemsWithNotAmbiguousKeys);

            return $result;

        }

        return array_merge($destination, $source);
    }

    /**
     * @param mixed[] $array
     * @param string $prefix
     * @return mixed[]
     */
    private function prefixArrayKeys(array $array, string $prefix): array
    {
        $keys = array_map(
            fn (string $key): string => $prefix . $key,
            array_keys($array),
        );

        $result = array_combine($keys, $array);

        if ($result === false) {
            throw new \LogicException('array_combine failed.');
        }

        return $result;
    }

}
