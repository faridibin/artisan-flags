<?php

namespace Faridibin\Laraflags\Console;

use Faridibin\Laraflags\Facades\Laraflags;
use Illuminate\Console\Command;

class InstallCommand extends Command
{
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
        if (LaraFlags::installed()) {
            $this->info('Laraflags is already installed.');
            $reinstall = $this->choice('Do you want to reinstall it?', ['yes', 'no'], 'no');

            if ($reinstall === 'no') {
                return Command::SUCCESS;
            }
        }

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
