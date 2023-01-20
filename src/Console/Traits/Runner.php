<?php

namespace Faridibin\Laraflags\Console\Traits;

use Faridibin\Laraflags\Facades\Laraflags;
use Illuminate\Console\Command;

trait Runner
{
    /**
     * Checks if Laraflags is installed.
     *
     * @param bool $reinstall
     * @return mixed
     */
    protected function checkInstallation(bool $shouldReinstall = false)
    {
        if (Laraflags::installed() && $shouldReinstall) {
            $this->info('Laraflags is already installed.');
            $reinstall = $this->choice('Do you want to reinstall it?', ['yes', 'no'], 'no');

            if ($reinstall === 'no') {
                return Command::SUCCESS;
            }
        }

        if (!Laraflags::installed()) {
            $this->error('Laraflags is not installed. Please run "php artisan laraflags:install"');

            return Command::FAILURE;
        }

        return $this;
    }

    /**
     * Checks if tenancy is enabled.
     *
     * @return mixed
     */
    public function checkTenancy()
    {
        if (!Laraflags::tenancyEnabled()) {
            $this->error('Laraflags do not have tenancy enabled!');

            return Command::FAILURE;
        }

        return $this;
    }
}
