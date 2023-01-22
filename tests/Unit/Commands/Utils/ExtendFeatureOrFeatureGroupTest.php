<?php

namespace Faridibin\Laraflags\Tests\Unit\Commands\Utils;

use Faridibin\Laraflags\Tests\TestCase;
use Faridibin\Laraflags\Models\Features;
use Faridibin\Laraflags\Models\FeatureGroups;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExtendFeatureOrFeatureGroupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_extend_feature_or_feature_group_command_extends_a_feature()
    {
        $feature = Features::factory()->create(['name' => 'test-feature']);
        $days = $this->faker->numberBetween(1, 10);
        $expiryDate = now()->addDays($days);

        $this->artisan('laraflags:extend', [
            'what' => 'feature',
            '--name' => 'test-feature',
            '--extend' =>  $days
        ])
            ->expectsOutput('The feature [test-feature] was extended! Expires at: ' . $expiryDate->format('Y-m-d H:i:s'))
            ->assertExitCode(0);

        $this->assertEquals($expiryDate->format('Y-m-d H:i:s'), $feature->fresh()->expires_at->format('Y-m-d H:i:s'));
    }

    /** @test */
    public function the_extend_feature_or_feature_group_command_extends_a_feature_group()
    {
        $group = FeatureGroups::factory()->create(['name' => 'test-feature-group']);
        $days = $this->faker->numberBetween(1, 10);
        $expiryDate = now()->addDays($days);

        $this->artisan('laraflags:extend', [
            'what' => 'feature-group',
            '--name' => 'test-feature-group',
            '--extend' =>  $days
        ])
            ->expectsOutput('The feature group [test-feature-group] was extended! Expires at: ' . $expiryDate->format('Y-m-d H:i:s'))
            ->assertExitCode(0);

        $this->assertEquals($expiryDate->format('Y-m-d H:i:s'), $group->fresh()->expires_at->format('Y-m-d H:i:s'));
    }

    /** @test */
    public function the_extend_feature_or_feature_group_command_prompts_for_a_feature_name_if_not_provided()
    {
        $feature = Features::factory()->create(['name' => 'test-feature']);
        $days = $this->faker->numberBetween(1, 10);
        $expiryDate = now()->addDays($days);

        $this->artisan('laraflags:extend', [
            'what' => 'feature',
            '--extend' =>  $days
        ])
            ->expectsQuestion('Please enter the name of the feature', 'test-feature')
            ->expectsOutput('The feature [test-feature] was extended! Expires at: ' . $expiryDate->format('Y-m-d H:i:s'))
            ->assertExitCode(0);

        $this->assertEquals($expiryDate->format('Y-m-d H:i:s'), $feature->fresh()->expires_at->format('Y-m-d H:i:s'));
    }

    /** @test */
    public function the_extend_feature_or_feature_group_command_prompts_for_a_feature_group_name_if_not_provided()
    {
        $group = FeatureGroups::factory()->create(['name' => 'test-feature-group']);
        $days = $this->faker->numberBetween(1, 10);
        $expiryDate = now()->addDays($days);

        $this->artisan('laraflags:extend', [
            'what' => 'feature-group',
            '--extend' =>  $days
        ])
            ->expectsQuestion('Please enter the name of the feature group', 'test-feature-group')
            ->expectsOutput('The feature group [test-feature-group] was extended! Expires at: ' . $expiryDate->format('Y-m-d H:i:s'))
            ->assertExitCode(0);

        $this->assertEquals($expiryDate->format('Y-m-d H:i:s'), $group->fresh()->expires_at->format('Y-m-d H:i:s'));
    }
}
