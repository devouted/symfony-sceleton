<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $statusCode = $exception instanceof HttpExceptionInterface 
            ? $exception->getStatusCode() 
            : 500;

        $response = new JsonResponse([
            'error' => [
                'code' => $statusCode,
                'message' => $exception->getMessage(),
                'type' => $this->getErrorType($statusCode)
            ]
        ], $statusCode);

        $event->setResponse($response);
    }

    private function getErrorType(int $statusCode): string
    {
        return match($statusCode) {
            400 => 'bad_request',
            401 => 'unauthorized',
            403 => 'forbidden',
            404 => 'not_found',
            422 => 'validation_error',
            500 => 'internal_server_error',
            default => 'error'
        };
    }
}
