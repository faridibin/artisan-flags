<?php

namespace Faridibin\Laraflags\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

trait HasConfigurations
{
    /**
     * Checks if Laraflags is installed.
     *
     * @return bool
     */
    public function installed(): bool
    {
        $installed = Schema::hasTable('features')
            && Schema::hasTable('feature_groups')
            && Schema::hasTable('feature_feature_group');

        if ($this->tenancyEnabled()) {
            $installed = $installed
                && Schema::hasTable('feature_tenant')
                && Schema::hasTable('feature_group_tenant');
        }

        return $installed;
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
