<?php

declare(strict_types = 1);

namespace App\Exceptions;

final class SshException extends \Exception
{
    /**
     * @phpstan-return never
     *
     * @throws \Exception
     */
    public static function InvalidServerConfig(string $message = ''): never
    {
        $message = $message === '' ? $message : " [{$message}]";

        throw new self("ERROR: Invalid SSH Server Config{$message}.");
    }
}
