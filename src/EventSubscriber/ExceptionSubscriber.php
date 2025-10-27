<?php

namespace App\EventSubscriber;

use App\Shared\Domain\Exception\BaseException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $httpCode  = 500;
        
        //  Recuperamos la excepción
        $exception = $event->getThrowable();
        $error = [
            'message' => $exception->getMessage(),
        ];

        // Si es una exception nuestra controlada, recuperamos el código HTTP que se debe devolver
        if ($exception instanceof BaseException) {
            $httpCode = $exception->getCode();
        }

        // Set Response to event
        $event->setResponse(new JsonResponse($error, $httpCode));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }
}
