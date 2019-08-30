<?php
declare(strict_types=1);

namespace App\Api;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Doctrine\ORM\EntityManagerInterface;
use App\Model\Horse as HorseModel;
use App\Entity\Horse as HorseEntity;
use App\Repository\HorseRepository;
use App\Exception\InvalidParametersException;
use App\Exception\NotFoundException;

/**
 * Class Horse
 * @package App\Api
 */
class Horse
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * Horse constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @param string $id
     * @return HorseModel
     * @throws NotFoundException
     */
    public function getById(string $id): HorseModel
    {
        /** @var HorseRepository $horseRepo */
        $horseRepo = $this->entityManager->getRepository(HorseEntity::class);

        /** @var HorseEntity $dbHorse */
        $dbHorse = $horseRepo->findOneBy(
            [
                'id' => $id
            ]
        );

        if ($dbHorse === null) {
            throw new NotFoundException('Entry with ID \'' . $id . '\' does not exist');
        }

        return new HorseModel($dbHorse);
    }

    /**
     * @return HorseModel[]
     */
    public function getAll(): array
    {
        /** @var HorseRepository $horseRepo */
        $horseRepo = $this->entityManager->getRepository(HorseEntity::class);

        $dbHorses = $horseRepo->findAll();

        $horses = [];
        foreach ($dbHorses as $dbHorse) {
            $horses[] = new HorseModel($dbHorse);
        }

        return $horses;
    }

    /**
     * @param HorseModel $horse
     * @throws InvalidParametersException
     */
    public function add(HorseModel $horse): void
    {
        $errors = $this->validator->validate($horse);

        if (count($errors) > 0) {
            $message = '';
            /** @var ConstraintViolation $error */
            foreach ($errors as $error) {
                $message .= !empty($message) ? ', ' : '';
                $message .= 'field \'' . $error->getPropertyPath() . '\' = ' . $error->getMessage();
            }
            throw new InvalidParametersException('Failed to validate input data: ' . $message);
        }

        $newHorse = new HorseEntity();
        $newHorse
            ->setName($horse->getName())
            ->setPicture($horse->getPicture());

        $this->entityManager->persist($newHorse);
        $this->entityManager->flush();
    }
}
