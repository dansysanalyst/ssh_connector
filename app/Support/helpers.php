<?php

declare(strict_types = 1);

if (! function_exists('home_path')) {
    function home_path(string $path): string
    {
        return str($_SERVER['HOME'])
            ->append("/$path")
            ->replace(['/', '\\'], DIRECTORY_SEPARATOR)
            ->replaceMatches('#' . DIRECTORY_SEPARATOR . '+#', DIRECTORY_SEPARATOR)
            ->toString();
    }
}
