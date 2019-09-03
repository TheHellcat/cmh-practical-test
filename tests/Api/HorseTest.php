<?php

namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use App\Api\Horse as HorseApi;
use App\Entity\Horse as HorseEntity;
use App\Model\Horse as HorseModel;
use App\Repository\HorseRepository;
use App\Exception\NotFoundException;
use App\Exception\InvalidParametersException;

/**
 * Class HorseTest
 * @package App\Tests\Api
 */
class HorseTest extends WebTestCase
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var HorseRepository
     */
    private $horseRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var HorseApi
     */
    private $horseApi;

    /**
     * Test getById() from the horses API
     */
    public function testGetById(): void
    {
        // test if we properly find and return an entry
        $horse = $this->horseApi->getById('TEST00000');
        $this->assertInstanceOf(HorseModel::class, $horse);
        $this->assertEquals('Testhorse', $horse->getName());

        // test if we properly handle NOT finding an entry
        $this->expectException(NotFoundException::class);
        $horse = $this->horseApi->getById('TEST00001');
    }

    /**
     * Test getAll() from the horses API
     */
    public function testGetAll(): void
    {
        // test if we properly find and return an entry
        $horses = $this->horseApi->getAll();
        $this->assertEquals(5, count($horses));
        $this->assertInstanceOf(HorseModel::class, $horses[1]);
        $this->assertInstanceOf(HorseModel::class, $horses[3]);
        $this->assertEquals('Testhorse 1', $horses[1]->getName());
        $this->assertEquals('Testhorse 3', $horses[3]->getName());
    }

    /**
     * Test add() from the horses API
     */
    public function testAdd(): void
    {
        $horseEntityGood = new HorseModel();
        $horseEntityGood
            ->setName('Testhorse')
            ->setPicture('http://test.tst/test.jpg');

        $horseEntityMissingName = new HorseModel();
        $horseEntityMissingName
            ->setName('')
            ->setPicture('http://test.tst/test.jpg');

        $horseEntityInvalidUrl = new HorseModel();
        $horseEntityInvalidUrl
            ->setName('Testhorse')
            ->setPicture('test.jpg');

        $this->horseApi->add($horseEntityGood);

        $this->expectExceptionMessageRegExp('/name.*not be blank/');
        $this->horseApi->add($horseEntityMissingName);

        $this->expectExceptionMessageRegExp('/picture.*not a valid URL/');
        $this->horseApi->add($horseEntityInvalidUrl);
    }

    /**
     * Initialize stuff used by several tests
     */
    public function setUp(): void
    {
        // boot SF kernel, so we can fetch services the tested class depends on but we don't want/need to mock
        self::bootKernel();
        $this->validator = self::$container->get('validator');

        // set up mock objects
        $this->horseRepository = $this->createMock(HorseRepository::class);
        $this->horseRepository->expects($this->any())
            ->method('findOneBy')
            ->willReturnCallback(self::class . '::cbFindOneBy');
        $this->horseRepository->expects($this->any())
            ->method('findAll')
            ->willReturnCallback(self::class . '::cbFindAll');

        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($this->horseRepository);
        $this->entityManager->expects($this->any())
            ->method('persist')
            ->willReturn(null);
        $this->entityManager->expects($this->any())
            ->method('flush')
            ->willReturn(null);

        $this->horseApi = new HorseApi($this->entityManager, $this->validator);
    }

    /**
     * Callback method to be called when the tested class calls "findOneBy()" from the mock repository.
     * Simulates finding an entry with the correct ID and not finding any results with any other ID.
     *
     * @param $criteria
     * @return HorseEntity|null
     */
    public static function cbFindOneBy($criteria)
    {
        $horseEntity = null;
        if (is_array($criteria) && isset($criteria['id']) && ($criteria['id'] == 'TEST00000')) {
            $horseEntity = new HorseEntity();
            $horseEntity
                ->setId('TEST00000')
                ->setName('Testhorse')
                ->setPicture('http://test.tst/test.jpg');
        }
        return $horseEntity;
    }

    /**
     * Callback method to be called when the tested class calls "findAll()" from the mock repository.
     * Simulates fetching entries from the database.
     *
     * @return HorseEntity[]|null
     */
    public static function cbFindAll()
    {
        $horseEntities = [];
        for ($i = 0; $i < 5; $i++) {
            $horseEntity = new HorseEntity();
            $horseEntity
                ->setId('TEST0000' . (string)$i)
                ->setName('Testhorse ' . (string)$i)
                ->setPicture('http://test.tst/test' . (string)$i . '.jpg');
            $horseEntities[] = $horseEntity;
        }
        return $horseEntities;
    }
}
