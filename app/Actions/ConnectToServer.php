<?php

declare(strict_types = 1);

namespace App\Actions;

use App\Support\Server;
use Illuminate\Support\Facades\Process;

final class ConnectToServer
{
    public static function handle(Server $server): void
    {
        Process::forever()->tty()->run($server->sshCommand());
    }
}
