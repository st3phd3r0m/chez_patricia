<?php

namespace App\EventSubscriber;

use App\Representation\NotFoundObject;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class NotFoundExceptionSubscriber implements EventSubscriberInterface
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $throwable = $event->getThrowable();
        if($throwable instanceof NotFoundHttpException){
            
            $notFoundObject = new NotFoundObject($throwable->getCode(), $throwable->getMessage());

            $body = $this->serializer->serialize($notFoundObject, 'json');

            $event->setResponse(new Response($body, $throwable->getCode()));

        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
