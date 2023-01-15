<?php

namespace Faridibin\Laraflags\Console\Feature;

use Faridibin\Laraflags\Facades\Laraflags;
use Faridibin\Laraflags\Models\Features;
use Faridibin\Laraflags\Models\FeatureGroups;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class DetachFromGroup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraflags:detach-from-group {feature?} {--g|group=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Detaches a feature flag from a feature group';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $feature = $this->handleGetFeaturePrompt();
        $groups = $this->handleGetFeatureGroupPrompt();

        if ($groups->isNotEmpty()) {
            $groups->each(function ($group) use ($feature) {
                if ($group === 'all') {
                    $feature->groups()->detach();

                    $this->comment("The feature [{$feature->name}] was detached from all feature groups!");
                } elseif ($group instanceof FeatureGroups) {
                    $feature->groups()->detach($group);

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
        $groups = $this->option('group');

        if (is_array($groups) && in_array('all', $groups)) {
            return collect(['all']);
        }

        $groups = collect($groups);

        if ($groups->isEmpty()) {
            $name = null;

            while (!$name) {
                $name = $this->ask('Please enter the name of the feature group to detach');
            }

            $groups->push($name);
        }

        return $groups->map(function ($name) {
            $group = Laraflags::group($name);

            if ($group) {
                return $group;
            }

            return $name;
        });
    }
}
