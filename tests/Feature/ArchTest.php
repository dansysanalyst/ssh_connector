<?php

declare(strict_types = 1);

arch('application must follow the defined PHP architectural rules')
    ->preset()
    ->php();

arch('application must follow security best practices')
    ->preset()
    ->security();

arch('globals')
    ->expect(['dd', 'dump', 'ray', 'ds', 'die', 'var_dump', 'sleep', 'exit'])
    ->not->toBeUsed();

it('ensures `env` is only used in config files')
    ->expect('env')
    ->not->toBeUsed()
    ->ignoring('config');
