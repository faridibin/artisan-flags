<?php

namespace Faridibin\Laraflags\Console\FeatureGroup;

use Faridibin\Laraflags\Console\Traits\Runner;
use Faridibin\Laraflags\Facades\Laraflags;
use Faridibin\Laraflags\Models\Features;
use Faridibin\Laraflags\Models\FeatureGroups;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class DetachFeature extends Command
{
    use Runner;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraflags:detach-feature {group?} {--f|feature=*}';

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
        $this->checkInstallation();

        $group = $this->handleGetFeatureGroupPrompt();
        $features = $this->handleGetFeaturePrompt();

        if ($features->isNotEmpty()) {
            $features->each(function ($feature) use ($group) {
                if ($feature === 'all') {
                    $group->features()->detach();

                    $this->comment("All features were detached from [{$group->name}]!");
                } elseif ($feature instanceof Features) {
                    $group->features()->detach($feature);

                    $this->comment("The feature [{$feature->name}] was detached from feature group [{$group->name}]!");
                } else {
                    $this->info("The feature option [{$group}] was skipped.");
                }
            });
        }

        return Command::SUCCESS;
    }

    /**
     * Prompts the user for a feature group name.
     *
     * @return FeatureGroups
     */
    private function handleGetFeatureGroupPrompt(): FeatureGroups
    {
        $name = $this->argument('group');

        if (!$name) {
            $name = $this->ask('Please enter the name of the feature group');
        }

        $group = Laraflags::group($name);

        while (!$group) {
            $this->alert("A feature group does not exist with this name [{$name}]");
            $name = $this->ask('Please enter the name of the feature group');

            $group = Laraflags::group($name);
        }

        return $group;
    }

    /**
     * Prompts the user for a feature name.
     *
     * @return Collection
     */
    private function handleGetFeaturePrompt(): Collection
    {
        $features = $this->option('feature');

        if (is_array($features) && in_array('all', $features)) {
            return collect(['all']);
        }

        $features = collect($features);

        if ($features->isEmpty()) {
            $name = null;

            while (!$name) {
                $name = $this->ask('Please enter the name of the feature to detach');
            }

            $features->push($name);
        }

        return $features->map(function ($name) {
            $feature = Laraflags::feature($name);

            if ($feature) {
                return $feature;
            }

            return $name;
        });
    }
}
