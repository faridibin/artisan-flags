<?php

namespace App\Console\Commands\Feature;

use App\Traits\Console\FeatureFlags;
use Illuminate\Console\Command;

class DeactivateFeatureGroup extends Command
{
    // use FeatureFlags;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraflags:deactivate-feature group {featureGroup?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivates a feature group';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $featureGroup = $this->argument('featureGroup');

        if (!$featureGroup) {
            $featureGroup = $this->ask('Please enter the name of the feature group');
        }

        $featureGroupExists = $this->doesFeatureGroupExist($featureGroup);

        while (!$featureGroupExists) {
            $this->alert('The feature group does not exist!');
            $featureGroup = $this->ask('Please enter the name of the feature group');

            $featureGroupExists = $this->doesFeatureGroupExist($featureGroup);
        }

        if ($featureGroupExists->isActivated()) {
            $featureGroupExists->deactivate();

            $this->info("The feature group [{$featureGroupExists->name}] was deactivated!");
        } else {
            $this->alert('The feature group is already deactivated!');
        }

        return Command::SUCCESS;
    }
}
