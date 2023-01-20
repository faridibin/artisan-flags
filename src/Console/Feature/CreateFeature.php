<?php

namespace Faridibin\Laraflags\Console\Feature;

use Faridibin\Laraflags\Console\Traits\CreatesFeature;
use Faridibin\Laraflags\Console\Traits\CreatesFeatureGroup;
use Faridibin\Laraflags\Console\Traits\Runner;
use Faridibin\Laraflags\Facades\Laraflags;
use Faridibin\Laraflags\Models\Features;
use Faridibin\Laraflags\Models\FeatureGroups;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class CreateFeature extends Command
{
    use CreatesFeature, CreatesFeatureGroup, Runner;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraflags:create-feature {name?} {--g|group=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new feature flag';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->checkInstallation();

        $feature = $this->handleFeatureCreationPrompt();

        if ($feature) {
            $this->info("The feature [{$feature->name}] was created!");
        }

        $groups = $this->handleFeatureGroupCreationPrompt();

        if ($groups->isNotEmpty()) {
            $groups->each(function ($group) use ($feature) {
                if ($group instanceof FeatureGroups) {
                    $feature->groups()->attach($group);

                    $this->comment("The feature [{$feature->name}] was attached to feature group [{$group->name}]!");
                } else {
                    $this->info("The feature group option [{$group}] was skipped.");
                }
            });
        }

        return Command::SUCCESS;
    }

    /**
     * Prompts the user for feature creation.
     *
     * @return \Faridibin\Laraflags\Models\Features
     */
    private function handleFeatureCreationPrompt(): Features
    {
        $name = $this->argument('name');

        if (!$name) {
            $name = $this->ask('Please enter a name for the feature');
        }

        $exists = Laraflags::feature($name);

        while ($exists) {
            $this->info('A feature already exists with this name.');
            $name = $this->ask('Please enter a new name for the feature');

            $exists = Laraflags::feature($name);
        }

        return $this->createFeature($name);
    }

    /**
     * Prompts the user for a feature-group name.
     *
     * @return \Illuminate\Support\Collection
     */
    private function handleFeatureGroupCreationPrompt(): Collection
    {
        $groups = collect($this->option('group'));

        return $groups->map(function ($name) {
            $group = Laraflags::group($name);

            if ($group) {
                return $group;
            }

            $this->info("A feature group [{$name}] does not exist.");
            $create = $this->choice("Do you want to create a new feature group with this name [{$name}]?", ['no', 'yes']);

            if ($create === 'yes') {
                return $this->createFeatureGroup($name);
            }

            return $name;
        });
    }
}
