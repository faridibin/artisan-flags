<?php

namespace Faridibin\Laraflags\Console;

use Faridibin\Laraflags\Facades\Laraflags;
use Illuminate\Console\Command;

class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraflags:publish {--views : Only publish the views} {--config : Only publish the config file} {--migrations : Only publish the migrations} {--force : Overwrite any existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish all of the Laraflags resources';

    /**
     * The console command hidden.
     *
     * @var bool
     */
    protected $hidden = true;

    /**
     * The overwrite option.
     *
     * @var bool
     */
    protected $overwrite = true;

    /**
     * The tag option.
     *
     * @var string
     */
    protected $tag = null;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->initTag();

        if (is_null($this->tag)) {
            $this->tag = $this->choice('Please select what you want to publish', ['config', 'views']);
        }

        switch ($this->tag) {
            case 'laraflags-config':
                $this->publishConfig();
                break;
            case 'laraflags-views':
                $this->publishViews();
                break;
            case 'laraflags-migrations':
                $this->publishMigrations();
                break;
            default:
                $this->error('Invalid tag.');
                return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * Get the overwrite value.
     *
     * @return bool
     */
    private function getOverwrite(): bool
    {
        return $this->overwrite;
    }

    /**
     * Set the overwrite value.
     *
     * @param bool $overwrite
     * @return void
     */
    private function setOverwrite($overwrite): void
    {
        $this->overwrite = $overwrite;
    }

    /**
     * Publish the configuration file.
     *
     * @return void
     */
    private function publishConfig(): void
    {
        if (LaraFlags::configExists()) {
            $overwrite = $this->choice('Laraflags config file already exists. Do you want to overwrite it?', ['yes', 'no'], 'no');

            if ($overwrite === 'yes') {
                $this->info('Overwriting Laraflags config file.');
            } else {
                $this->info('Skipped publishing Laraflags config file.');
            }

            $this->setOverwrite($overwrite === 'yes');
        }

        if ($this->getOverwrite()) {
            $this->publish();
        } else {
            $this->setOverwrite(true);
        }
    }

    /**
     * Publish the views.
     *
     * @return void
     */
    private function publishViews(): void
    {
        if (LaraFlags::viewsExist()) {
            $overwrite = $this->choice('Laraflags views already exists. Do you want to overwrite them?', ['yes', 'no'], 'no');

            if ($overwrite === 'yes') {
                $this->info('Overwriting Laraflags views.');
            } else {
                $this->info('Skipped publishing Laraflags views.');
            }

            $this->setOverwrite($overwrite === 'yes');
        }

        if ($this->getOverwrite()) {
            $this->publish();
        } else {
            $this->setOverwrite(true);
        }
    }

    /**
     * Publish the migrations.
     *
     * @return void
     */
    private function publishMigrations(): void
    {
        if (LaraFlags::migrationsExist()) {
            $overwrite = $this->choice('Laraflags migrations already exists. Do you want to overwrite them?', ['yes', 'no'], 'no');

            if ($overwrite === 'yes') {
                $this->info('Overwriting Laraflags migrations.');
            } else {
                $this->info('Skipped publishing Laraflags migrations.');
            }

            $this->setOverwrite($overwrite === 'yes');
        }

        if ($this->getOverwrite()) {
            $this->publish();
        } else {
            $this->setOverwrite(true);
        }
    }

    /**
     * Publish the files.
     *
     * @param string $tag
     * @return void
     */
    private function publish(string $tag = null): void
    {
        $this->call('vendor:publish', [
            '--provider' => 'Faridibin\Laraflags\LaraflagsServiceProvider',
            '--tag' => $this->tag ?? $tag,
            '--force' => $this->getOverwrite(),
        ]);
    }

    /**
     * Get the tag to publish.
     *
     * @return void
     */
    private function initTag(): void
    {
        if ($this->option('config')) {
            $this->tag = 'laraflags-config';
        }

        if ($this->option('views')) {
            $this->tag = 'laraflags-views';
        }

        if ($this->option('migrations')) {
            $this->tag = 'laraflags-migrations';
        }
    }
}
