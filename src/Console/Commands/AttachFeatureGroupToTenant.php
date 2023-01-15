<?php

namespace App\Console\Commands\Feature;

use App\Models\Feature\FeatureGroups;
use App\Traits\Console\FeatureFlags;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class AttachFeatureGroupToTenant extends Command
{
    // use FeatureFlags;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraflags:attach-group-to-tenant {group?} {--t|tenant=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Attach a feature group to a tenant';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!config('feature-flags.tenancy.enabled', false)) {
            $this->error('Feature-flags do not have tenancy enabled!');

            return Command::FAILURE;
        }

        $featureGroup = $this->handleFeatureGroupPrompt();
        $tenants = $this->handleTenantPrompt();

        foreach ($tenants['found'] as $tenant) {
            $identifier = config('feature-flags.tenancy.identifier');
            if ($featureGroup->hasTenant($tenant->name)) {
                $this->comment("The feature group [{$featureGroup->name}] is already attached to tenant [{$tenant->$identifier}]!");

                continue;
            }

            $featureGroup->tenants()->attach($tenant);

            $this->comment("The feature group [{$featureGroup->name}] was attached to tenant [{$tenant->$identifier}]!");
        }

        if (count($tenants['notFound']) > 0) {
            $notFound = Arr::join($tenants['notFound'], ', ', ' and ');

            $this->alert("These tenants do not exist! [{$notFound}]");
        }

        return Command::SUCCESS;
    }

    /**
     * Prompts the user for a feature group name.
     *
     * @return \App\Models\Feature\FeatureGroups
     */
    private function handleFeatureGroupPrompt(): FeatureGroups
    {
        $featureGroup = $this->argument('group');

        if (!$featureGroup) {
            $featureGroup = $this->ask('Please enter the name of the feature group');
        }

        $featureGroupExists = $this->doesFeatureGroupExist($featureGroup);

        while (!$featureGroupExists) {
            $this->alert("A feature does not exist with this name [{$featureGroup}]");
            $featureGroup = $this->ask('Please enter a name of the feature');

            $featureGroupExists = $this->doesFeatureGroupExist($featureGroup);
        }

        return $featureGroupExists;
    }

    /**
     * Prompts the user for a tenant name.
     *
     * @return array
     */
    private function handleTenantPrompt(): array
    {
        $data = [
            'found' => [],
            'notFound' => [],
        ];

        $tenants = $this->option('tenant');

        if (!$tenants) {
            $tenants[] = $this->ask('Please enter the name of the tenant');
        }

        foreach ($tenants as $tenant) {
            $tenantExists = $this->doesTenantExist($tenant);

            if ($tenantExists) {
                $data['found'][] = $tenantExists;

                continue;
            }

            $data['notFound'][] = $tenant;
        }

        return $data;
    }
}
