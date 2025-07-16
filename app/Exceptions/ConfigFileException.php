<?php

declare(strict_types = 1);

namespace App\Exceptions;

final class ConfigFileException extends \Exception
{
    /**
     * @phpstan-return never
     *
     * @throws \Exception
     */
    public static function fileNotFound(string $configFilePath): never
    {
        throw new self("ERROR: File not found {$configFilePath}.");
    }

    /**
     * @param  array<int, string>  $configFiles
     *
     * @phpstan-return never
     *
     * @throws \Exception
     */
    public static function noFilesFound(array $configFiles = []): never
    {
        $configFiles = count($configFiles) > 0 ? ' [' . implode(', ', $configFiles) . ']' : '';

        throw new self("ERROR: Could not find any of the SSH config files{$configFiles}.");
    }
}
