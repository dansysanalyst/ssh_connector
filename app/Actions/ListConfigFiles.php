<?php

declare(strict_types = 1);

namespace App\Actions;

use App\Exceptions\ConfigFileException;
use Illuminate\Support\Facades\File;

/**
 * throws ConfigFileException
 */
final class ListConfigFiles
{
    public static function handle(): string
    {
        $configFiles    = config('ssh.config_files', []);

        $configFilePath = collect($configFiles)
            ->filter(fn (string $filePath): bool => File::exists($filePath));

        if ($configFilePath->isEmpty()) {
            ConfigFileException::NoFilesFound((array) $configFiles);
        }

        return $configFilePath->first();
    }
}
