<?php

namespace App\Infrastructure\EventListeners;

use App\Infrastructure\Exception\InvalidInputException;
use App\Infrastructure\Exception\ValidationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class KernelExceptionListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 2],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if ($exception instanceof ValidationException) {
            $event->setResponse(
                new JsonResponse(['errors' => $exception->getValidationErrors()], Response::HTTP_BAD_REQUEST)
            );
        }

        if ($exception instanceof InvalidInputException) {
            $event->setResponse(
                new JsonResponse(['error' => $exception->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY)
            );
        }
    }
}
