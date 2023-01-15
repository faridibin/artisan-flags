<?php

namespace App\Console\Commands\Feature;

use App\Traits\Console\FeatureFlags;
use Illuminate\Console\Command;

class ActivateFeatureGroup extends Command
{
    // use FeatureFlags;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraflags:activate-feature group {featureGroup?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activates a feature group';

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
            $this->alert('The feature group is already activated!');
        } else {
            $featureGroupExists->activate();

            $this->info("The feature group [{$featureGroupExists->name}] was activated!");
        }

        return Command::SUCCESS;
    }
}
