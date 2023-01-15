<?php

namespace Faridibin\Laraflags\Console\Feature;

use Faridibin\Laraflags\Facades\Laraflags;
use Illuminate\Console\Command;

class DeactivateFeature extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraflags:deactivate-feature {feature?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivates a feature flag';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('feature');

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
            $feature->deactivate();

            $this->info("The feature [{$feature->name}] was deactivated!");
        } else {
            $this->alert('The feature is already deactivated!');
        }

        return Command::SUCCESS;
    }
}
