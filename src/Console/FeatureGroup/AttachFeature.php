<?php

namespace Faridibin\Laraflags\Console\FeatureGroup;

use Faridibin\Laraflags\Console\Traits\CreatesFeature;
use Faridibin\Laraflags\Console\Traits\Runner;
use Faridibin\Laraflags\Facades\Laraflags;
use Faridibin\Laraflags\Models\Features;
use Faridibin\Laraflags\Models\FeatureGroups;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class AttachFeature extends Command
{
    use CreatesFeature, Runner;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraflags:attach-feature {group?} {--f|feature=*}';

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

        $group = $this->handleGetFeatureGroupPrompt();
        $features = $this->handleGetFeaturePrompt();

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
        $features = collect($this->option('feature'));

        if ($features->isEmpty()) {
            $name = null;

            while (!$name) {
                $name = $this->ask('Please enter the name of the feature to attach');
            }

            $features->push($name);
        }

        return $features->map(function ($name) {
            $feature = Laraflags::feature($name);

            if (!$feature) {
                $this->info("A feature [{$name}] does not exist.");
                $creates = $this->choice("Do you want to create a feature with this name [{$name}]?", ['no', 'yes']);

                if ($creates === 'yes') {
                    $feature = $this->createFeature($name);
                }
            }

            return $feature;
        });
    }
}
