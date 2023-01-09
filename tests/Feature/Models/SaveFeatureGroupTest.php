<?php

namespace Faridibin\Laraflags\Tests\Feature\Models;

use Faridibin\Laraflags\Tests\TestCase;
use Faridibin\Laraflags\Models\FeatureGroups;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SaveFeatureGroupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_save_a_feature_group()
    {
        $featureGroup = FeatureGroups::factory()->create();

        $this->assertDatabaseHas('feature_groups', [
            'id' => $featureGroup->id,
        ]);
    }

    /** @test */
    public function it_can_save_a_feature_group_with_a_name()
    {
        $featureGroup = FeatureGroups::factory()->create([
            'name' => 'test',
        ]);

        $this->assertDatabaseHas('feature_groups', [
            'id' => $featureGroup->id,
            'name' => 'test',
        ]);
    }

    /** @test */
    public function it_can_save_a_feature_group_with_a_description()
    {
        $featureGroup = FeatureGroups::factory()->create([
            'description' => 'test',
        ]);

        $this->assertDatabaseHas('feature_groups', [
            'id' => $featureGroup->id,
            'description' => 'test',
        ]);
    }

    /** @test */
    public function it_can_save_a_feature_group_with_an_active()
    {
        $featureGroup = FeatureGroups::factory()->create([
            'active' => false,
        ]);

        $this->assertDatabaseHas('feature_groups', [
            'id' => $featureGroup->id,
            'active' => false,
        ]);
    }

    /** @test */
    public function it_can_save_a_feature_group_with_an_expires_at()
    {
        $featureGroup = FeatureGroups::factory()->create([
            'expires_at' => now()->addDays(1),
        ]);

        $this->assertDatabaseHas('feature_groups', [
            'id' => $featureGroup->id,
            'expires_at' => now()->addDays(1),
        ]);
    }
}
