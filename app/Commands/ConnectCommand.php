<?php

declare(strict_types = 1);

namespace App\Commands;

use App\Actions\{ConnectToServer, ListConfigFiles, ParseConfigFile};

use App\Support\Server;

use function Laravel\Prompts\{error, select};

use LaravelZero\Framework\Commands\Command;

final class ConnectCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'connect {--file=}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Connect to SSH Server';

    protected string $sshConfigFile;

    public function handle(): int
    {
        try {
            $this->sshConfigFile = is_null($this->option('file')) ?
                ListConfigFiles::handle() :
                strval($this->option('file'));

            ConnectToServer::handle($this->selectServer());

            return self::SUCCESS;
        } catch (\Exception $e) {
            error($e->getMessage());

            return self::FAILURE;
        }
    }

    private function selectServer(): Server
    {
        $servers = collect(ParseConfigFile::handle($this->sshConfigFile));

        $selectedServer = select(
            label: 'Select a server',
            hint: "Loaded from: {$this->sshConfigFile}",
            options: $servers->mapwithKeys(fn (Server $server) => $server->asMenuOption())->toArray(),
            scroll: 30
        );

        return $servers->first(function (Server $server) use ($selectedServer): bool {
            /* @phpstan-ignore-next-line */
            return $server->id === $selectedServer;
        });
    }
}
