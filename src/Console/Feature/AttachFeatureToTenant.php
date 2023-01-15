<?php

namespace App\Console\Commands\Feature;

use App\Models\Feature\Features;
use App\Traits\Console\FeatureFlags;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class AttachFeatureToTenant extends Command
{
    // use FeatureFlags;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraflags:attach-to-tenant {feature?} {--t|tenant=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Attaches a feature flag to a tenant';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // if (!config('feature-flags.tenancy.enabled', false)) {
        //     $this->error('Feature-flags do not have tenancy enabled!');

        //     return Command::FAILURE;
        // }

        // $feature = $this->handleFeaturePrompt();
        // $tenants = $this->handleTenantPrompt();

        // foreach ($tenants['found'] as $tenant) {
        //     $identifier = config('feature-flags.tenancy.identifier');
        //     if ($feature->hasTenant($tenant->name)) {
        //         $this->comment("The feature [{$feature->name}] is already attached to tenant [{$tenant->$identifier}]!");

        //         continue;
        //     }

        //     $feature->tenants()->attach($tenant);

        //     $this->comment("The feature [{$feature->name}] was attached to tenant [{$tenant->$identifier}]!");
        // }

        // if (count($tenants['notFound']) > 0) {
        //     $notFound = Arr::join($tenants['notFound'], ', ', ' and ');

        //     $this->alert("These tenants do not exist! [{$notFound}]");
        // }

        return Command::SUCCESS;
    }

    // /**
    //  * Prompts the user for a feature name.
    //  *
    //  * @return Features
    //  */
    // private function handleFeaturePrompt(): Features
    // {
    //     $feature = $this->argument('feature');

    //     if (!$feature) {
    //         $feature = $this->ask('Please enter the name of the feature');
    //     }

    //     $featureExists = $this->doesFeatureExist($feature);

    //     while (!$featureExists) {
    //         $this->alert("A feature does not exist with this name [{$feature}]");
    //         $feature = $this->ask('Please enter a name of the feature');

    //         $featureExists = $this->doesFeatureExist($feature);
    //     }

    //     return $featureExists;
    // }

    // /**
    //  * Prompts the user for a tenant name.
    //  *
    //  * @return array
    //  */
    // private function handleTenantPrompt(): array
    // {
    //     $data = [
    //         'found' => [],
    //         'notFound' => [],
    //     ];

    //     $tenants = $this->option('tenant');

    //     if (!$tenants) {
    //         $tenants[] = $this->ask('Please enter the name of the tenant');
    //     }

    //     foreach ($tenants as $tenant) {
    //         $tenantExists = $this->doesTenantExist($tenant);

    //         if ($tenantExists) {
    //             $data['found'][] = $tenantExists;

    //             continue;
    //         }

    //         $data['notFound'][] = $tenant;
    //     }

    //     return $data;
    // }
}
