<?php
declare(strict_types=1);

namespace App\Exception;

/**
 * Interface AppExceptionInterface
 * @package App\Exception
 */
interface AppExceptionInterface
{
    /**
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * @return string
     */
    public function getStatusMessage(): string;
}
