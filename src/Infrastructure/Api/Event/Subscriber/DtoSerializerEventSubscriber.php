<?php

namespace App\Infrastructure\Api\Event\Subscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Serializes the request data to the given DTO
 */
final class DtoSerializerEventSubscriber implements EventSubscriberInterface
{
    /**
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     */
    public function __construct(private SerializerInterface $serializer, private ValidatorInterface $validator)
    {}

    /**
     * @param RequestEvent $event
     */
    public function serializeToDto(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (false === $request->attributes->has('dto')
            || false === in_array($request->getMethod(), [Request::METHOD_POST, Request::METHOD_PUT, Request::METHOD_PATCH], true)
        ) {
            return;
        }

        $dto = $this->serializer->deserialize($request->getContent(), $request->attributes->get('dto'), 'json');
        $this->validator->validate($dto);

        $request->attributes->set('dto', $dto);
    }

    /**
     * @return array[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['serializeToDto', EventPriorities::PRE_READ],
        ];
    }
}