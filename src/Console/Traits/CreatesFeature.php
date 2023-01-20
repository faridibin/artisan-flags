<?php

namespace Faridibin\Laraflags\Console\Traits;

use Faridibin\Laraflags\Models\Features;
use Faridibin\Laraflags\Services\FeatureService;

trait CreatesFeature
{
    /**
     * Handle the feature creation.
     *
     * @return \Faridibin\Laraflags\Models\Features
     */
    protected function createFeature(string $name): Features
    {
        $service = new FeatureService();

        if (!$name) {
            $name = $this->ask('Please enter a name for the feature');
        }

        $description = $this->ask('Please enter a description for the feature');
        $isActive = $this->choice('Do you want to activate this feature?', ['no', 'yes'], 'yes');

        if (config('laraflags.expiration.enabled')) {
            $expiresAt = $this->ask('When do you want this feature to expire? (Number of Days)', 0);
        }

        return $service->create([
            'name' => $name,
            'description' => $description,
            'active' => $isActive === 'yes',
            'expires_at' => isset($expiresAt) ? now()->addDays($expiresAt) : null,
        ]);
    }
}
