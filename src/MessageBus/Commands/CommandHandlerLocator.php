<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\Commands;

use Psr\Container\ContainerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;
use Symfony\Component\Messenger\Handler\HandlersLocatorInterface;

final class CommandHandlerLocator implements HandlersLocatorInterface
{

    private ContainerInterface $container;

    /**
     * @var array<class-string, string>
     */
    private array $serviceNameByMessageType;

    /**
     * @param ContainerInterface $container
     * @param array<class-string, string> $serviceNameByMessageType
     */
    public function __construct(ContainerInterface $container, array $serviceNameByMessageType)
    {
        $this->container = $container;
        $this->serviceNameByMessageType = $serviceNameByMessageType;
    }

    /**
     * @param Envelope $envelope
     * @return iterable<int, HandlerDescriptor>
     */
    public function getHandlers(Envelope $envelope): iterable
    {
        $messageType = get_class($envelope->getMessage());
        if (! isset($this->serviceNameByMessageType[$messageType])) {
            throw new \LogicException("Could not find handler for $messageType.");
        }

        $serviceName = $this->serviceNameByMessageType[$messageType];
        $service = $this->container->get($serviceName);
        assert(is_callable($service));
        yield new HandlerDescriptor($service);
    }

}
