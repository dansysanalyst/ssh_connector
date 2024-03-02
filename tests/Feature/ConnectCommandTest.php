<?php

declare(strict_types = 1);

it('can render a server list', function () {
    $this->artisan('connect')->assertExitCode(0);
})->todo();
