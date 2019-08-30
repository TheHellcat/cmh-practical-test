<?php
declare(strict_types=1);

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\Serializer;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
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
     * @SWG\Response(
     *     response=200,
     *     description="Returns details of one horse",
     *     @Model(type=\App\Model\Response\HorseCollection::class)
     *     )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Horse with given ID not found",
     *     @Model(type=\App\Model\Response\BadRequest::class)
     *     )
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="string",
     *     description="ID of the horse to fetch"
     * )
     * @SWG\Tag(name="horses-api")
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
     * @SWG\Response(
     *     response=200,
     *     description="Returns details of all horses",
     *     @Model(type=\App\Model\Response\HorseCollection::class)
     *     )
     * )
     * @SWG\Tag(name="horses-api")
     *
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
     * @SWG\Response(
     *     response=201,
     *     description="Adds another horse",
     *     @Model(type=\App\Model\Response\SimpleStatus::class)
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation and other errors",
     *     @Model(type=\App\Model\Response\BadRequest::class)
     *     )
     * )
     * @SWG\Parameter(
     *     name="data",
     *     in="body",
     *     @Model(type=\App\Model\Horse::class),
     *     description="Data of the horse to add"
     * )
     * @SWG\Tag(name="horses-api")
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
