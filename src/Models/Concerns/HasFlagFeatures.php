<?php

namespace Faridibin\Laraflags\Models\Concerns;

trait HasFlagFeatures
{
    /**
     * Activate the given model.
     *
     * @return bool
     */
    public function activate(): void
    {
        $this->active = true;
        $this->save();
    }

    /**
     * Deactivate the given model.
     *
     * @return bool
     */
    public function deactivate(): void
    {
        $this->active = false;
        $this->save();
    }

    /**
     * Determine if the model is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    //     /**
    //      * The feature groups that feature belongs to.
    //      */
    //     public function groups(): BelongsToMany
    //     {
    //         return $this->belongsToMany(FeatureGroups::class, 'feature_feature_group', 'feature_id', 'feature_group_id')->withTimestamps();
    //     }

    //     /**
    //      * The tenants that feature belongs to.
    //      */
    //     public function tenants(): BelongsToMany
    //     {
    //         return $this->belongsToMany(config('feature-flags.tenancy.model'), 'feature_tenant', 'feature_id', 'tenant_id')->withTimestamps();
    //     }

    //     /**
    //      * Determine if the feature group has a given tenant.
    //      *
    //      * @param string $tenant
    //      * @return bool
    //      */
    //     public function hasTenant($tenant): bool
    //     {
    //         return $this->tenants->contains('name', $tenant);
    //     }

    //     /**
    //      * Attach a tenant to the feature.
    //      *
    //      * @param \App\Models\Tenant $tenant
    //      * @return void
    //      */
    //     public function attachTenant(Tenant $tenant)
    //     {
    //         $this->tenants()->attach($tenant);
    //     }

    //     /**
    //      * Detach a tenant from the feature.
    //      *
    //      * @param \App\Models\Tenant $tenant
    //      * @return void
    //      */
    //     public function detachTenant(Tenant $tenant)
    //     {
    //         $this->tenants()->detach($tenant);
    //     }

    //     /**
    //      * Determine if the feature belongs to a given group.
    //      *
    //      * @param string $group
    //      * @return bool
    //      */
    //     public function inGroup($group): bool
    //     {
    //         return $this->groups->contains('name', $group);
    //     }

    //     /**
    //      * Determine if the feature is active.
    //      *
    //      * @return bool
    //      */
    //     public function isActivated(): bool
    //     {
    //         return $this->active;
    //     }

    //     /**
    //      * Determine if the feature is expired.
    //      *
    //      * @return bool
    //      */
    //     public function isExpired(): bool
    //     {
    //         return $this->expires_at && $this->expires_at->isPast();
    //     }

    //     /**
    //      * Extend the feature expiration date.
    //      *
    //      * @return void
    //      */
    //     public function extend(string $date)
    //     {
    //         $this->expires_at = $date;

    //         $this->save();
    //     }


    // /**
    //  * The features that belongs to the feature group.
    //  */
    // public function features(): BelongsToMany
    // {
    //     return $this->belongsToMany(Features::class, 'feature_feature_group', 'feature_group_id', 'feature_id')->withTimestamps();
    // }

    // /**
    //  * The tenants that belongs to the feature group.
    //  */
    // public function tenants(): BelongsToMany
    // {
    //     return $this->belongsToMany(config('feature-flags.tenancy.model'), 'feature_group_tenant', 'feature_group_id', 'tenant_id')->withTimestamps();
    // }

    // /**
    //  * Determine if the feature group has a given feature.
    //  *
    //  * @param string $feature
    //  * @return bool
    //  */
    // public function hasFeature($feature): bool
    // {
    //     return $this->features->contains('name', $feature);
    // }

    // /**
    //  * Determine if the feature group has a given tenant.
    //  *
    //  * @param string $tenant
    //  * @return bool
    //  */
    // public function hasTenant($tenant): bool
    // {
    //     return $this->tenants->contains('name', $tenant);
    // }

    // /**
    //  * Attach a feature to the feature group.
    //  *
    //  * @param \App\Models\Feature\Features $feature
    //  * @return void
    //  */
    // public function attachFeature(Features $feature)
    // {
    //     $this->features()->attach($feature);
    // }

    // /**
    //  * Detach a feature from the feature group.
    //  *
    //  * @param \App\Models\Feature\Features $feature
    //  * @return void
    //  */
    // public function detachFeature(Features $feature)
    // {
    //     $this->features()->detach($feature);
    // }

    // /**
    //  * Attach a tenant to the feature group.
    //  *
    //  * @param \App\Models\Tenant $tenant
    //  * @return void
    //  */
    // public function attachTenant(Tenant $tenant)
    // {
    //     $this->tenants()->attach($tenant);
    // }

    // /**
    //  * Detach a tenant from the feature group.
    //  *
    //  * @param \App\Models\Tenant $tenant
    //  * @return void
    //  */
    // public function detachTenant(Tenant $tenant)
    // {
    //     $this->tenants()->detach($tenant);
    // }



    // /**
    //  * Determine if the feature group is expired.
    //  *
    //  * @return bool
    //  */
    // public function isExpired(): bool
    // {
    //     return $this->expires_at && $this->expires_at->isPast();
    // }

    // /**
    //  * Extend the expiration date of the feature group.
    //  *
    //  * @return void
    //  */
    // public function extend(string $date)
    // {
    //     $this->expires_at = $date;

    //     $this->save();
    // }
}
