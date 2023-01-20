<?php

namespace Faridibin\Laraflags\Tests\Unit\Commands\Feature;

use Faridibin\Laraflags\Tests\TestCase;
use Faridibin\Laraflags\Models\Features;
use Faridibin\Laraflags\Models\FeatureGroups;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DetachFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_command_can_detach_a_feature_from_a_feature_group()
    {
        $group = FeatureGroups::factory()->create(['name' => 'test-feature-group']);
        $feature = Features::factory()->create(['name' => 'test-feature']);

        $group->features()->attach($feature);

        $this->artisan('laraflags:detach-feature', [
            'group' => $group->name,
            '--feature' => $feature->name,
        ]);

        $this->assertDatabaseMissing('feature_feature_group', [
            'feature_group_id' => $group->id,
            'feature_id' => $feature->id,
        ]);
    }

    /** @test */
    public function the_command_can_detach_a_feature_from_multiple_feature_groups()
    {
        $group = FeatureGroups::factory()->create(['name' => 'test-feature-group']);
        $feature1 = Features::factory()->create(['name' => 'test-feature-1']);
        $feature2 = Features::factory()->create(['name' => 'test-feature-2']);

        $group->features()->attach([$feature1->id, $feature2->id]);

        $this->artisan('laraflags:detach-feature', [
            'group' => $group->name,
            '--feature' => [$feature1->name, $feature2->name],
        ]);

        $this->assertDatabaseMissing('feature_feature_group', [
            'feature_group_id' => $group->id,
            'feature_id' => $feature1->id,
        ]);

        $this->assertDatabaseMissing('feature_feature_group', [
            'feature_group_id' => $group->id,
            'feature_id' => $feature2->id,
        ]);
    }

    /** @test */
    public function the_command_can_detach_a_feature_from_all_feature_groups()
    {
        $group = FeatureGroups::factory()->create(['name' => 'test-feature-group']);
        $feature1 = Features::factory()->create(['name' => 'test-feature-1']);
        $feature2 = Features::factory()->create(['name' => 'test-feature-2']);

        $group->features()->attach([$feature1->id, $feature2->id]);

        $this->artisan('laraflags:detach-feature', [
            'group' => $group->name,
            '--feature' => ['all'],
        ]);

        $this->assertDatabaseMissing('feature_feature_group', [
            'feature_group_id' => $group->id,
            'feature_id' => $feature1->id,
        ]);

        $this->assertDatabaseMissing('feature_feature_group', [
            'feature_group_id' => $group->id,
            'feature_id' => $feature2->id,
        ]);
    }
}
