<?php

declare(strict_types = 1);

namespace App\Actions;

use App\Exceptions\ConfigFileException;
use App\Support\Server;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

final class ParseConfigFile
{
    /**
     * @return Collection<int, Server>
     */
    public static function handle(string $configFilePath): Collection
    {
        if (! File::exists($configFilePath)) {
            ConfigFileException::fileNotFound($configFilePath);
        }

        return collect(json_decode(File::get($configFilePath), true))
            /** @phpstan-ignore-next-line */
            ->map(fn (array $server): Server => Server::fromArray($server))
            ->sortBy('title');
    }
}
