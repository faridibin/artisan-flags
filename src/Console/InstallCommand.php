<?php

namespace Faridibin\Laraflags\Console;

use Faridibin\Laraflags\Console\Traits\Runner;
use Illuminate\Console\Command;

class InstallCommand extends Command
{
    use Runner;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraflags:install {--with-migrations : Publish migrations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the commands necessary to prepare Laraflags for use';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->checkInstallation(true);

        $this->info('Installing Laraflags...');

        // Publish the configuration file.
        $this->call('laraflags:publish', ['--config' => true, '--force' => true]);

        // Publish views.
        $this->call('laraflags:publish', ['--views' => true, '--force' => true]);

        if ($this->option('with-migrations')) {
            // Publish migrations.
            $this->call('laraflags:publish', ['--migrations' => true, '--force' => true]);
        }

        $this->info('Laraflags was installed successfully!');

        return Command::SUCCESS;
    }
}
