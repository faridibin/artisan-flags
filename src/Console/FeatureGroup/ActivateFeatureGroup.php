<?php

namespace Faridibin\Laraflags\Console\FeatureGroup;

use Faridibin\Laraflags\Console\Traits\Runner;
use Faridibin\Laraflags\Facades\Laraflags;
use Illuminate\Console\Command;

class ActivateFeatureGroup extends Command
{
    use Runner;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraflags:activate-feature-group {name?}';

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
        $this->checkInstallation();

        $name = $this->argument('name');

        if (!$name) {
            $name = $this->ask('Please enter the name of the feature group');
        }

        $group = Laraflags::group($name);

        while (!$group) {
            $this->alert('The feature group does not exist!');
            $name = $this->ask('Please enter the name of the feature group');

            $group = Laraflags::group($name);
        }

        if ($group->isActive()) {
            $this->alert("The feature group [{$group->name}] is already activated!");
        } else {
            $group->activate();

            $this->info("The feature group [{$group->name}] was activated!");
        }

        return Command::SUCCESS;
    }
}
