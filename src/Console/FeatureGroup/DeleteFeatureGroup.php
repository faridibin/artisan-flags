<?php

namespace Faridibin\Laraflags\Console\FeatureGroup;

use Faridibin\Laraflags\Console\Traits\Runner;
use Faridibin\Laraflags\Facades\Laraflags;
use Illuminate\Console\Command;

class DeleteFeatureGroup extends Command
{
    use Runner;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraflags:delete-feature-group {name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes a feature flag';

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

        $group->delete();

        $this->info("The feature group [{$name}] was deleted!");

        return Command::SUCCESS;
    }
}
