<?php

declare(strict_types = 1);

return [

    /*
    |--------------------------------------------------------------------------
    | SSH Config Files
    |--------------------------------------------------------------------------
    | Config files ordered by priority of usage.
    |
    */

    'config_files' => [
        'ssh_servers.json',
        home_path('ssh_servers.json'),
        home_path('/.config/ssh_servers.json'),
    ],
];
