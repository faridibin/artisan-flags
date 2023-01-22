<?php

namespace Faridibin\Laraflags\Console\Utils;

use Faridibin\Laraflags\Console\Traits\Runner;
use Faridibin\Laraflags\Facades\Laraflags;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ExtendFeatureOrFeatureGroup extends Command
{
    use Runner;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraflags:extend {what?} {--N|name=} {--e|extend=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extends a feature or feature group';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->checkInstallation();

        $what = Str::replace('-', ' ', $this->argument('what'));
        $name = $this->option('name');
        $expiresAt = $this->option('extend');

        if (!$what) {
            $what = $this->choice('What would you like to extend?', ['feature', 'feature group']);
        }

        if (!$name) {
            $name = $this->ask("Please enter the name of the {$what}");
        }

        $exists = $this->exists($what, $name);

        while (!$exists) {
            $this->alert("The {$what} does not exist!");
            $name = $this->ask("Please enter the name of the {$what}");

            $exists = $this->exists($what, $name);
        }

        if (!$expiresAt) {
            $expiresAt = $this->ask("When do you want this {$what} to expire? (Number of Days)", 0);
        }

        $exists->extend($expiresAt);

        $this->info("The {$what} [{$exists->name}] was extended! Expires at: {$exists->expires_at}");

        return Command::SUCCESS;
    }
    /**
     * Check if a feature or feature group exists.
     *
     * @param string $what
     * @param string $name
     * @return mixed
     */
    private function exists(string $what, string $name): mixed
    {
        return match ($what) {
            'feature' => Laraflags::feature($name),
            'feature group' => Laraflags::group($name),
        };
    }
}
