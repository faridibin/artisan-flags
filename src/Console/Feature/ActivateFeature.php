<?php

namespace Faridibin\Laraflags\Console\Feature;

use Faridibin\Laraflags\Console\Traits\Runner;
use Faridibin\Laraflags\Facades\Laraflags;
use Illuminate\Console\Command;

class ActivateFeature extends Command
{
    use Runner;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraflags:activate-feature {name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activates a feature flag';

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
            $name = $this->ask('Please enter the name of the feature');
        }

        $feature = Laraflags::feature($name);

        while (!$feature) {
            $this->alert('The feature does not exist!');
            $name = $this->ask('Please enter the name of the feature');

            $feature = Laraflags::feature($name);
        }

        if ($feature->isActive()) {
            $this->alert("The feature [{$feature->name}] is already activated!");
        } else {
            $feature->activate();

            $this->info("The feature [{$feature->name}] was activated!");
        }

        return Command::SUCCESS;
    }
}
