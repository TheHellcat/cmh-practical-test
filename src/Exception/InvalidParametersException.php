<?php
declare(strict_types=1);

namespace App\Exception;

/**
 * Class InvalidParametersException
 * @package App\Exception
 */
class InvalidParametersException extends \Exception implements AppExceptionInterface
{
    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return 400;
    }

    /**
     * @return string
     */
    public function getStatusMessage(): string
    {
        return 'invalid data';
    }
}
