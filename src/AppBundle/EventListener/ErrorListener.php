<?php

namespace JustMeet\AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class ErrorListener
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        $response = new JsonResponse([
            'error' => $exception->getMessage()
        ], $exception->getCode() ?: 500);

        $event->setResponse($response);
    }
}
