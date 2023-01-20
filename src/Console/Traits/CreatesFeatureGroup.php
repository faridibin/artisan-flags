<?php

namespace Faridibin\Laraflags\Console\Traits;

use Faridibin\Laraflags\Models\FeatureGroups;
use Faridibin\Laraflags\Services\FeatureGroupService;

trait CreatesFeatureGroup
{
    /**
     * Handle the feature group creation.
     *
     * @return \Faridibin\Laraflags\Models\FeatureGroups
     */
    public function createFeatureGroup(string $name): FeatureGroups
    {
        $service = new FeatureGroupService();

        if (!$name) {
            $name = $this->ask('Please enter a name for the feature group');
        }

        $description = $this->ask('Please enter a description for the feature group');
        $isActive = $this->choice('Do you want to activate this feature group?', ['no', 'yes'], 'yes');

        if (config('laraflags.expiration.enabled')) {
            $expiresAt = $this->ask('When do you want this feature group to expire? (Number of Days)', 0);
        }

        return $service->create([
            'name' => $name,
            'description' => $description,
            'active' => $isActive === 'yes',
            'expires_at' => isset($expiresAt) ? now()->addDays($expiresAt) : null,
        ]);
    }
}
