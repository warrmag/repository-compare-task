<?php
declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    const MESSAGE = 'Exception thrown without message';

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getException();

        $responseMessage = [
            'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => $exception->getMessage()
        ];

        if (method_exists($exception, 'getStatusCode') && $exception->getStatusCode()) {
            $responseMessage['code'] = $exception->getStatusCode();
        }

        if ($exception->getCode()) {
            $responseMessage['code'] = $exception->getCode();
        }

        if (!$exception->getMessage()) {
            $responseMessage['message'] = self::MESSAGE;
        }

        $event->setResponse(new JsonResponse($responseMessage, $responseMessage['code']));

        return $responseMessage;
    }
}