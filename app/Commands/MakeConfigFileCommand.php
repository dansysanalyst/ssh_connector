<?php

declare(strict_types = 1);

namespace App\Commands;

use Illuminate\Support\Facades\File;

use function Laravel\Prompts\{error, info};

use LaravelZero\Framework\Commands\Command;

final class MakeConfigFileCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'make:config';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Generate a SSH config file.';

    public function handle(): int
    {
        $filePath = 'ssh_servers.json';

        if (File::exists($filePath)) {
            error("File [{$filePath}] already exists. Aborting!");

            return self::FAILURE;
        }

        File::put($filePath, (string) json_encode([
            [
                'title'       => 'My SSH Server',
                'group'       => 'personal',
                'hostname'    => '127.0.0.1',
                'user'        => 'foo',
                'port'        => 22,
                'tunnel_port' => null,
                'keyfile'     => '/home/user/.ssh/my_key',
            ],
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        info("File [$filePath] created successfully!");

        return self::SUCCESS;
    }
}
