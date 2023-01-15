<?php

namespace Faridibin\Laraflags\Tests\Unit\Commands\Feature;

use Faridibin\Laraflags\Tests\TestCase;
use Faridibin\Laraflags\Models\Features;
use Faridibin\Laraflags\Models\FeatureGroups;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DetachFromGroupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_command_can_detach_a_feature_from_a_feature_group()
    {
        $feature = Features::factory()->create(['name' => 'test-feature']);
        $group = FeatureGroups::factory()->create(['name' => 'test-feature-group']);

        $feature->groups()->attach($group);

        $this->artisan('laraflags:detach-from-group', [
            'feature' => $feature->name,
            '--group' => $group->name,
        ]);

        $this->assertDatabaseMissing('feature_feature_group', [
            'feature_id' => $feature->id,
            'feature_group_id' => $group->id,
        ]);
    }

    /** @test */
    public function the_command_can_detach_a_feature_from_multiple_feature_groups()
    {
        $feature = Features::factory()->create(['name' => 'test-feature']);
        $group1 = FeatureGroups::factory()->create(['name' => 'test-feature-group-1']);
        $group2 = FeatureGroups::factory()->create(['name' => 'test-feature-group-2']);

        $feature->groups()->attach([$group1->id, $group2->id]);

        $this->artisan('laraflags:detach-from-group', [
            'feature' => $feature->name,
            '--group' => [$group1->name, $group2->name],
        ]);

        $this->assertDatabaseMissing('feature_feature_group', [
            'feature_id' => $feature->id,
            'feature_group_id' => $group1->id,
        ]);

        $this->assertDatabaseMissing('feature_feature_group', [
            'feature_id' => $feature->id,
            'feature_group_id' => $group2->id,
        ]);
    }

    /** @test */
    public function the_command_can_detach_a_feature_from_all_feature_groups()
    {
        $feature = Features::factory()->create(['name' => 'test-feature']);
        $group1 = FeatureGroups::factory()->create(['name' => 'test-feature-group-1']);
        $group2 = FeatureGroups::factory()->create(['name' => 'test-feature-group-2']);

        $feature->groups()->attach([$group1->id, $group2->id]);

        $this->artisan('laraflags:detach-from-group', [
            'feature' => $feature->name,
            '--group' => ['all'],
        ]);

        $this->assertDatabaseMissing('feature_feature_group', [
            'feature_id' => $feature->id,
            'feature_group_id' => $group1->id,
        ]);

        $this->assertDatabaseMissing('feature_feature_group', [
            'feature_id' => $feature->id,
            'feature_group_id' => $group2->id,
        ]);
    }
}
