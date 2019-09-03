<?php
declare(strict_types=1);

namespace App\Exception;

/**
 * Class InvalidParametersException
 * @package App\Exception
 */
class AuthenticationException extends \Exception implements AppExceptionInterface
{
    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return 401;
    }

    /**
     * @return string
     */
    public function getStatusMessage(): string
    {
        return 'not authenticated';
    }
}
