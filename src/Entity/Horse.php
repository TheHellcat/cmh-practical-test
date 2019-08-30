<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Horse
 *
 * @ORM\Entity(repositoryClass="App\Repository\HorseRepository")
 * @ORM\Table(name="horse")
 *
 * @package Entity
 */
class Horse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string", length=36)
     *
     * @var string
     */
    private $id;

    /**
     * @ORM\Column(type="string", name="name", nullable=false, length=255)
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", name="picture_url", nullable=true)
     *
     * @var string
     */
    private $picture;

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
