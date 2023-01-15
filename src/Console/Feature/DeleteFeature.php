<?php

namespace Faridibin\Laraflags\Console\Feature;

use Faridibin\Laraflags\Facades\Laraflags;
use Illuminate\Console\Command;

class DeleteFeature extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraflags:delete-feature {feature?}';

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

        $feature->delete();

        $this->info("The feature [{$name}] was deleted!");

        return Command::SUCCESS;
    }
}
