<?php
declare(strict_types=1);

namespace App\Exception;

/**
 * Class InvalidParametersException
 * @package App\Exception
 */
class NotFoundException extends \Exception implements AppExceptionInterface
{
    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return 404;
    }

    /**
     * @return string
     */
    public function getStatusMessage(): string
    {
        return 'not found';
    }
}
