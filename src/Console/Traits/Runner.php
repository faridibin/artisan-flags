<?php

namespace Faridibin\LaraFlags\Console\Commands\Traits;

use Faridibin\LaraFlags\Facades\LaraFlags;

trait Runner
{
    /**
     * Check if Laraflags is installed.
     *
     * @return void
     */
    protected function checkConfig()
    {
        if (!Laraflags::installed()) {
            return $this->error('Laraflags is not installed. Please run "php artisan laraflags:install"');
        }
    }
}
