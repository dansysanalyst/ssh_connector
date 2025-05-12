<?php

declare(strict_types = 1);

namespace App\Commands;

use App\Actions\{ListConfigFiles, ParseConfigFile};
use App\Support\Server;

use function Laravel\Prompts\{error};

use LaravelZero\Framework\Commands\Command;

final class ShowSshCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'show {server} {--file=}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Show SSH connect command';

    public function handle(): int
    {
        try {
            $sshConfigFile = is_null($this->option('file')) ?
            ListConfigFiles::handle() :
            strval($this->option('file'));

            $servers        = collect(ParseConfigFile::handle($sshConfigFile));
            $selectedServer = $this->argument('server');

            $server = $servers->first(function (Server $server) use ($selectedServer): bool {
                return $server->title === $selectedServer;
            });

            if (! is_null($server)) {
                echo $server->toPairedString() . PHP_EOL;
            }

            return self::SUCCESS;
        } catch (\Exception $e) {
            error($e->getMessage());

            return self::FAILURE;
        }
    }
}
