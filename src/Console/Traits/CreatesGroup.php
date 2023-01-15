<?php

namespace Faridibin\LaraFlags\Console\Traits;

use Faridibin\LaraFlags\Models\FeatureGroups;
use Faridibin\Laraflags\Services\FeatureGroupService;

trait CreatesGroup
{
    /**
     * Handle the feature group creation.
     *
     * @return \Faridibin\LaraFlags\Models\FeatureGroups
     */
    public function createGroup(string $name): FeatureGroups
    {
        $service = new FeatureGroupService();

        if (!$name) {
            $name = $this->ask('Please enter a name for the feature group');
        }

        $description = $this->ask('Please enter a description for the feature group');
        $isActive = $this->choice('Do you want to activate this feature group?', ['no', 'yes'], 'yes');

        if (config('feature-flags.expiration.enabled')) {
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
