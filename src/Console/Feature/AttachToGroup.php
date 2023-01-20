<?php

namespace Faridibin\Laraflags\Console\Feature;

use Faridibin\Laraflags\Console\Traits\CreatesFeatureGroup;
use Faridibin\Laraflags\Console\Traits\Runner;
use Faridibin\Laraflags\Facades\Laraflags;
use Faridibin\Laraflags\Models\Features;
use Faridibin\Laraflags\Models\FeatureGroups;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class AttachToGroup extends Command
{
    use CreatesFeatureGroup, Runner;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraflags:attach-to-group {feature?} {--g|group=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Attaches a feature flag to a feature group';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->checkInstallation();

        $feature = $this->handleGetFeaturePrompt();
        $groups = $this->handleGetFeatureGroupPrompt();

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
     * Prompts the user for a feature name.
     *
     * @return Features
     */
    private function handleGetFeaturePrompt(): Features
    {
        $name = $this->argument('feature');

        if (!$name) {
            $name = $this->ask('Please enter the name of the feature');
        }

        $feature = Laraflags::feature($name);

        while (!$feature) {
            $this->alert("A feature does not exist with this name [{$name}]");
            $name = $this->ask('Please enter the name of the feature');

            $feature = Laraflags::feature($name);
        }

        return $feature;
    }

    /**
     * Prompts the user for a feature group name.
     *
     * @return Collection
     */
    private function handleGetFeatureGroupPrompt(): Collection
    {
        $groups = collect($this->option('group'));

        if ($groups->isEmpty()) {
            $name = null;

            while (!$name) {
                $name = $this->ask('Please enter the name of the feature group to attach to');
            }

            $groups->push($name);
        }

        return $groups->map(function ($name) {
            $group = Laraflags::group($name);

            if (!$group) {
                $this->info("A feature group [{$name}] does not exist.");
                $creates = $this->choice("Do you want to create a feature group with this name [{$name}]?", ['no', 'yes']);

                if ($creates === 'yes') {
                    $group = $this->createFeatureGroup($name);
                }
            }

            return $group;
        });
    }
}
