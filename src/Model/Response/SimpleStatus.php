<?php
declare(strict_types=1);

namespace App\Model\Response;

use JMS\Serializer\Annotation as Serializer;

class SimpleStatus
{
    /**
     * @Serializer\SerializedName("status")
     * @Serializer\Type("string")
     *
     * @var string
     */
    private $status;

    /**
     * @Serializer\SerializedName("message")
     * @Serializer\Type("string")
     *
     * @var string
     */
    private $message;

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return SimpleStatus
     */
    public function setStatus(string $status): SimpleStatus
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return SimpleStatus
     */
    public function setMessage(string $message): SimpleStatus
    {
        $this->message = $message;
        return $this;
    }
}
