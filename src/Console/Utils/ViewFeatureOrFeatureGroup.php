<?php

namespace Faridibin\Laraflags\Console\Utils;

use Faridibin\Laraflags\Services\FeatureService;
use Faridibin\Laraflags\Services\FeatureGroupService;
use Faridibin\Laraflags\Console\Traits\Runner;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ViewFeatureOrFeatureGroup extends Command
{
    use Runner;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraflags:view {what?} {--with-features} {--with-count}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'View a features or feature groups';

    protected $headers = ['Name', 'Description', 'Activated', 'Expires At'];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->checkInstallation();

        $what = Str::replace('-', ' ', $this->argument('what'));

        match ($what) {
            'feature' => $this->viewFeatures(),
            'feature group' => $this->viewFeatureGroups(),
            default => $this->viewAll(),
        };

        return Command::SUCCESS;
    }

    /**
     * View all features and feature groups.
     *
     * @return void
     */
    private function viewAll()
    {
        $this->viewFeatures();
        $this->line('');
        $this->viewFeatureGroups();
    }

    /**
     * View all features.
     *
     * @return void
     */
    private function viewFeatures()
    {
        $this->info('Features:');

        $features = $this->getAll('features')->map(function ($feature) {
            return [
                'name' => $feature->name,
                'description' => $feature->description,
                'active' => $feature->active,
                'expires_at' => $feature->expires_at,
            ];
        })->toArray();

        $this->table($this->headers, $features);
    }

    /**
     * View all feature groups.
     *
     * @return void
     */
    private function viewFeatureGroups()
    {
        $this->info('Feature Groups:');
        $headers = $this->headers;
        $groups = $this->getAll('feature-groups');

        if ($this->option('with-features')) {
            $headers = array_merge($headers, ['Features']);

            $table = $groups->map(function ($group) {
                return [
                    'name' => $group->name,
                    'description' => $group->description,
                    'active' => $group->active,
                    'expires_at' => $group->expires_at,
                    'features' => $this->option('with-count') ? $group->features()->count() : Arr::join($group->features()->pluck('name')->toArray(), ', ', ' and '),
                ];
            })->toArray();

            return $this->table($headers, $table);
        }

        $groups = $groups->map(function ($group) {
            return [
                'name' => $group->name,
                'description' => $group->description,
                'active' => $group->active,
                'expires_at' => $group->expires_at,
            ];
        })->toArray();

        $this->table($headers, $groups);
    }

    /**
     * Get all features or feature groups.
     *
     * @param string $name
     * @return mixed
     */
    private function getAll(string $name): mixed
    {
        $columns = ['id', 'name', 'description', 'active', 'expires_at'];

        return match ($name) {
            'features' => (new FeatureService())->getAll($columns),
            'feature-groups' => (new FeatureGroupService())->getAll($columns),
        };
    }
}
