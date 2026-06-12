<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\ServeCommand;

use function Illuminate\Support\php_binary;

class SafeServeCommand extends ServeCommand
{
    /**
     * Use public/index.php as router (built-in static-file handling; no extra files needed).
     */
    protected function serverCommand(): array
    {
        $router = public_path('index.php');
        $fallback = public_path('serve.php');

        if (! is_readable($router)) {
            if (! is_readable($fallback)) {
                throw new \RuntimeException('public/index.php is missing or not readable, and public/serve.php is missing or not readable.');
            }
            $router = $fallback;
        }

        return [
            php_binary(),
            '-S',
            $this->host().':'.$this->port(),
            $router,
        ];
    }
}
