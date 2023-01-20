<?php

namespace Faridibin\Laraflags\Console\FeatureGroup;

use Faridibin\Laraflags\Console\Traits\CreatesFeature;
use Faridibin\Laraflags\Console\Traits\CreatesFeatureGroup;
use Faridibin\Laraflags\Console\Traits\Runner;
use Faridibin\Laraflags\Facades\Laraflags;
use Faridibin\Laraflags\Models\Features;
use Faridibin\Laraflags\Models\FeatureGroups;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class CreateFeatureGroup extends Command
{
    use CreatesFeature, CreatesFeatureGroup, Runner;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraflags:create-feature-group {name?} {--f|feature=*}';

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
    public function handle()
    {
        $this->checkInstallation();

        $group = $this->handleFeatureGroupCreationPrompt();

        if ($group) {
            $this->info("The feature group [{$group->name}] was created!");
        }

        $features = $this->handleFeaturesCreationPrompt();

        if ($features->isNotEmpty()) {
            $features->each(function ($feature) use ($group) {
                if ($feature instanceof Features) {
                    $group->features()->attach($feature);

                    $this->comment("The feature [{$feature->name}] was attached to feature group [{$group->name}]!");
                } else {
                    $this->info("The feature option [{$feature}] was skipped.");
                }
            });
        }

        return Command::SUCCESS;
    }

    /**
     * Prompts the user for feature group creation.
     *
     * @return \Faridibin\Laraflags\Models\FeatureGroups
     */
    private function handleFeatureGroupCreationPrompt(): FeatureGroups
    {
        $name = $this->argument('name');

        if (!$name) {
            $name = $this->ask('Please enter a name for the feature group');
        }

        $exists = Laraflags::group($name);

        while ($exists) {
            $this->info('A feature group already exists with this name.');
            $name = $this->ask('Please enter a new name for the feature group');

            $exists = Laraflags::group($name);
        }

        return $this->createFeatureGroup($name);
    }

    /**
     * Handle the features prompt.
     *
     * @return Collection
     */
    private function handleFeaturesCreationPrompt(): Collection
    {
        $features = collect($this->option('feature'));

        return $features->map(function ($name) {
            $feature = Laraflags::feature($name);

            if ($feature) {
                return $feature;
            }

            $this->info("A feature [{$name}] does not exist.");
            $create = $this->choice("Do you want to create a new feature with this name [{$name}]?", ['no', 'yes']);

            if ($create === 'yes') {
                return $this->createFeature($name);
            }

            return $name;
        });
    }
}
