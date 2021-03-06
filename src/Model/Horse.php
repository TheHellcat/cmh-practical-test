<?php
declare(strict_types=1);

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use Swagger\Annotations as SWG;
use App\Entity\Horse as HorseEntity;

/**
 * Class Horse
 * @package App\Model
 */
class Horse
{
    /**
     * @Serializer\SerializedName("id")
     * @Serializer\Type("string")
     *
     * @SWG\Property(description="Unique ID of the entry - IGNORED for add requests")
     *
     * @var string
     */
    private $id;

    /**
     * @Serializer\SerializedName("name")
     * @Serializer\Type("string")
     *
     * @SWG\Property(description="Name of the Horse (can not be empty)")
     *
     * @Assert\NotBlank
     *
     * @var string
     */
    private $name;

    /**
     * @Serializer\SerializedName("picture")
     * @Serializer\Type("string")
     *
     * @SWG\Property(description="URL to picture of the horse (can be empty, must be valid URL if given)")
     *
     * @Assert\Url(
     *     protocols = {"http", "https"}
     * )
     *
     * @var string
     */
    private $picture;

    /**
     * Horse constructor.
     * @param HorseEntity|null $horseEntity
     */
    public function __construct(HorseEntity $horseEntity = null)
    {
        if ($horseEntity !== null) {
            $this->fromEntity($horseEntity);
        }
    }

    /**
     * @param HorseEntity $horse
     * @return $this
     */
    public function fromEntity(HorseEntity $horse)
    {
        $this
            ->setId($horse->getId())
            ->setName($horse->getName())
            ->setPicture($horse->getPicture());

        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Horse
     */
    public function setId(string $id): Horse
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Horse
     */
    public function setName(string $name): Horse
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPicture(): string
    {
        return $this->picture;
    }

    /**
     * @param string $picture
     * @return Horse
     */
    public function setPicture(string $picture): Horse
    {
        $this->picture = $picture;
        return $this;
    }
}
