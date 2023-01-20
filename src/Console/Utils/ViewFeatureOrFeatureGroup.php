<?php

namespace App\Console\Commands\Feature;

use App\Models\Feature\FeatureGroups;
use App\Services\Feature\FeatureGroupService;
use App\Services\Feature\FeatureService;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class ViewFeatureOrFeatureGroup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraflags:view {what?} {--with-features} {--count}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'View a features or feature groups';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(FeatureService $featureService, FeatureGroupService $featureGroupService)
    {
        $what = $this->argument('what');

        match ($what) {
            'features' => $this->viewFeatures($featureService),
            'feature groups' => $this->viewFeatureGroups($featureGroupService),
            default => $this->viewAll($featureService, $featureGroupService),
        };

        return Command::SUCCESS;
    }

    /**
     * View all features and feature groups.
     *
     * @return void
     */
    private function viewAll(FeatureService $featureService, FeatureGroupService $featureGroupService)
    {
        $this->viewFeatures($featureService);
        $this->line('');
        $this->viewFeatureGroups($featureGroupService);
    }

    /**
     * View all features.
     *
     * @param FeatureService $service
     * @return void
     */
    private function viewFeatures(FeatureService $service)
    {
        $this->info('Features:');
        $headers = ['Name', 'Description', 'Activated', 'Expires At'];

        $this->table($headers, $this->getAll($service));
    }

    /**
     * View all feature groups.
     *
     * @param FeatureGroupService $service
     * @return void
     */
    private function viewFeatureGroups(FeatureGroupService $service)
    {
        $this->info('Feature Groups:');
        $headers = ['Name', 'Description', 'Activated', 'Expires At'];
        $table = $this->getAll($service);

        if ($this->option('with-features')) {
            $headers = array_merge($headers, ['Features']);
            $groups = FeatureGroups::with('features')->get();

            foreach ($groups as $key => $group) {
                $table[$key]['features'] = $this->option('count') ? $group->features()->count() : Arr::join($group->features()->pluck('name')->toArray(), ', ', ' and ');
            }
        }

        $this->table($headers, $table);
    }

    /**
     * Get all features or feature groups.
     *
     * @param mixed $service
     * @return array
     */
    private function getAll($service): array
    {
        return $service->getAll([
            'name', 'description', 'active', 'expires_at'
        ])->toArray();
    }
}
