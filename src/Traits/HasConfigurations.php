<?php

namespace Faridibin\Laraflags\Traits;

use Illuminate\Support\Facades\File;

trait HasConfigurations
{
    /**
     * Checks if Laraflags is installed.
     *
     * @return bool
     */
    public function installed(): bool
    {
        return $this->configExists() && $this->viewsExist();
    }

    /**
     * Checks if Laraflags is configuration exists.
     *
     * @return bool
     */
    public function configExists(): bool
    {
        return File::exists(config_path('laraflags.php'));
    }

    /**
     * Checks if Laraflags views exists.
     *
     * @return bool
     */
    public function viewsExist(): bool
    {
        return File::exists(resource_path('views/vendor/laraflags'));
    }

    /**
     * Checks if Laraflags migrations exists.
     *
     * @return bool
     */
    public function migrationsExist(): bool
    {
        return File::exists(database_path('migrations/laraflags'));
    }

    /**
     * Checks if Laraflags tenancy is enabled.
     *
     * @return bool
     */
    public function tenancyEnabled(): bool
    {
        return config('laraflags.tenancy.enabled', false);
    }
}
