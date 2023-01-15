<?php

namespace App\Console\Commands\Feature;

use App\Services\Feature\FeatureGroupService;
use App\Services\Feature\FeatureService;
use App\Traits\Console\FeatureFlags;
use Illuminate\Console\Command;

class NewFeatureGroup extends Command
{
    // use FeatureFlags;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraflags:new-feature group {featureGroup?} {--f|feature=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new feature group';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(FeatureGroupService $featureGroupService, FeatureService $featureService)
    {
        $featureGroup = $this->handleFeatureGroupPrompt();
        $features = $this->handleFeaturesPrompt();

        if ($featureGroup) {
            $featureGroup = $featureGroupService->create($featureGroup);

            if ($featureGroup) {
                $this->info("The feature group [{$featureGroup->name}] was created!");
            }

            if ($features) {
                foreach ($features as $feature) {
                    if (isset($feature['exists']) && $feature['exists']) {
                        $feature = $feature['model'];
                    } else {
                        $feature = $featureService->create($feature);
                    }

                    $feature->groups()->attach($featureGroup);

                    $this->comment("The feature [{$feature->name}] was attached to feature group[{$featureGroup->name}]!");
                }
            }
        }

        return Command::SUCCESS;
    }

    /**
     * Handle the feature group prompt.
     *
     * @return array
     */
    private function handleFeatureGroupPrompt(): array
    {
        $featureGroup = $this->argument('featureGroup');

        if (!$featureGroup) {
            $featureGroup = $this->ask('Please enter a name for the feature');
        }

        $featureGroupExists = $this->doesFeatureGroupExist($featureGroup);

        while ($featureGroupExists) {
            $this->alert("A feature group already exists with this name. [{$featureGroup}]");
            $featureGroup = $this->ask('Please enter a new name for the feature group');

            $featureGroupExists = $this->doesFeatureGroupExist($featureGroup);
        }

        $description = $this->ask('Please enter a description for the feature group');
        $isActive = $this->choice('Do you want to activate this feature group?', ['no', 'yes'], 'yes');

        if (config('feature-flags.expiration.enabled')) {
            $expiresAt = $this->ask('When do you want this feature group to expire? (Number of Days)', 0);
        }

        return [
            'name' => $featureGroup,
            'description' => $description,
            'active' => $isActive === 'yes',
            'expires_at' => isset($expiresAt) ? now()->addDays($expiresAt) : null,
        ];
    }

    /**
     * Handle the features prompt.
     *
     * @return array
     */
    private function handleFeaturesPrompt(): array
    {
        $data = [];
        foreach ($this->option('feature') as $key => $option) {
            $feature = $option;

            $feature = $this->doesFeatureExist($feature);

            if ($feature) {
                $this->info("A feature already exists with this name [{$feature->name}].");
                $this->comment('The feature will be attached to the feature group.');

                $data[$key] = [
                    'model' => $feature,
                    'exists' => true,
                ];
            } else {
                $this->info("A feature [{$option}] does not exist.yes");
                $hasFeature = $this->choice("Do you want to create a feature with this name [{$option}]?", ['no', 'yes']);

                if ($hasFeature === 'yes') {
                    $feature = $option;
                    $description = $this->ask('Please enter a description for the feature');
                    $isActive = $this->choice('Do you want to activate this feature?', ['no', 'yes'], 'yes');

                    if (config('feature-flags.expiration.enabled')) {
                        $expiresAt = $this->ask('When do you want this feature to expire? (Number of Days)', 0);
                    }

                    $data[$key] = [
                        'name' => $feature,
                        'description' => $description,
                        'active' => $isActive === 'yes',
                        'expires_at' => isset($expiresAt) ? now()->addDays($expiresAt) : null,
                    ];
                }
            }
        }

        return $data;
    }
}
