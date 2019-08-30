<?php
declare(strict_types=1);

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\Serializer;
use App\Api\Horse as HorseApiService;
use App\Model\Response\HorseCollection;
use App\Model\Horse as HorseModel;
use App\Model\Response\SimpleStatus;

/**
 * Class Horse
 * @package App\Controller\Api
 */
class Horse extends AbstractController
{
    /**
     * @var Serializer
     */
    private $serializer = null;

    /**
     * Horse constructor.
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Route(
     *     "/horses/{id}",
     *     methods={"GET"},
     *     name="api_horses_get_one"
     * )
     *
     * @param string $id
     * @param HorseApiService $horseApi
     * @return JsonResponse
     */
    public function getHorseAction(string $id, HorseApiService $horseApi): JsonResponse
    {
        $horses = new HorseCollection();
        $horse = $horseApi->getById($id);
        $horses->addHorse($horse);

        $json = $this->serializer->serialize($horses, 'json');
        $response = new JsonResponse();
        $response->setJson($json);
        return $response;
    }

    /**
     * @Route(
     *     "/horses",
     *     methods={"GET"},
     *     name="api_horses_get_all"
     * )
     *
     * @param string $id
     * @param HorseApiService $horseApi
     * @return JsonResponse
     */
    public function getAllHorsesAction(HorseApiService $horseApi): JsonResponse
    {
        $horses = new HorseCollection();
        foreach ($horseApi->getAll() as $horse) {
            $horses->addHorse($horse);
        }

        $json = $this->serializer->serialize($horses, 'json');
        $response = new JsonResponse();
        $response->setJson($json);
        return $response;
    }

    /**
     * @Route(
     *     "/horses",
     *     methods={"POST"},
     *     name="api_horses_add"
     * )
     *
     * @param Request $request
     * @param HorseApiService $horseApi
     * @return JsonResponse
     */
    public function addHorseAction(Request $request, HorseApiService $horseApi): JsonResponse
    {
        $horse = $this->serializer->deserialize($request->getContent(), HorseModel::class, 'json');
        $horseApi->add($horse);

        $status = new SimpleStatus();
        $status
            ->setStatus('success')
            ->setMessage('Entry added');

        $json = $this->serializer->serialize($status, 'json');
        $response = new JsonResponse();
        $response->setJson($json);
        $response->setStatusCode(201);
        return $response;
    }
}
