<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\Events;

use Psr\Container\ContainerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;
use Symfony\Component\Messenger\Handler\HandlersLocatorInterface;

final readonly class EventSubscribersLocator implements HandlersLocatorInterface
{

    private ContainerInterface $container;

    /**
     * @var array<class-string, string[]>
     */
    private array $serviceNamesByMessageType;

    /**
     * @param array<class-string, string[]> $serviceNamesByMessageType
     */
    public function __construct(ContainerInterface $container, array $serviceNamesByMessageType)
    {
        $this->container = $container;
        $this->serviceNamesByMessageType = $serviceNamesByMessageType;
    }

    /**
     * @return iterable<int, HandlerDescriptor>
     */
    public function getHandlers(Envelope $envelope): iterable
    {
        $messageType = get_class($envelope->getMessage());
        foreach ($this->serviceNamesByMessageType[$messageType] ?? [] as $serviceName) {
            $service = $this->container->get($serviceName);
            assert(is_callable($service));
            yield new HandlerDescriptor($service);
        }
    }

}
