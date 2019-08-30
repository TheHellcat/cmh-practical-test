<?php
declare(strict_types=1);

namespace App\Model\Response;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;
use App\Model\Horse;

class HorseCollection
{
    /**
     * @Serializer\SerializedName("status")
     * @Serializer\Type("string")
     *
     * @var string
     */
    private $status;

    /**
     * @Serializer\SerializedName("horses")
     * @Serializer\Type("ArrayCollection<App\Model\Horse>")
     *
     * @var ArrayCollection|Horse[]
     */
    private $horses;

    /**
     * HorseCollection constructor.
     */
    public function __construct()
    {
        $this->horses = new ArrayCollection();
        $this->status = "ok";
    }

    /**
     * @return ArrayCollection|Horse[]
     */
    public function getHorses(): ArrayCollection
    {
        return $this->horses;
    }

    /**
     * @param Horse $horse
     * @return HorseCollection
     */
    public function addHorse(Horse $horse): HorseCollection
    {
        if (!$this->horses->contains($horse)) {
            $this->horses->add($horse);
        }

        return $this;
    }
}
