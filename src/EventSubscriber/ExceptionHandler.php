<?php
declare(strict_types=1);

namespace App\EventSubscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;
use App\Model\Response\BadRequest;
use App\Exception\NotFoundException;
use App\Exception\AuthenticationException;

/**
 * Class ExceptionHandler
 * @package App\EventSubscriber
 */
class ExceptionHandler implements EventSubscriberInterface
{
    /**
     * @var Serializer
     */
    private $serializer = null;

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['exceptionProcessor']
        ];
    }

    /**
     * Horse constructor.
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function exceptionProcessor(GetResponseForExceptionEvent $event)
    {
        $statusCode = 400;
        $statusText = 'error';

        $exception = $event->getException();

        if ($exception instanceof NotFoundException) {
            $statusCode = 404;
            $statusText = 'not found';
        }
        if ($exception instanceof AuthenticationException) {
            $statusCode = 401;
            $statusText = 'not authenticated';
        }

        $responseModel = new BadRequest();
        $responseModel
            ->setStatus($statusText)
            ->setMessage($exception->getMessage());

        $json = $this->serializer->serialize($responseModel, 'json');

        $response = new JsonResponse();
        $response->setJson($json);
        $response->setStatusCode($statusCode);
        $response->headers->set('X-Status-Code', $statusCode);

        $event->setResponse($response);
    }
}
