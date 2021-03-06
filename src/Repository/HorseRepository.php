<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Entity\Horse;

/**
 * Class HorseRepository
 * @package App\Repository
 */
class HorseRepository extends ServiceEntityRepository
{
    /**
     * HorseRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Horse::class);
    }
}
