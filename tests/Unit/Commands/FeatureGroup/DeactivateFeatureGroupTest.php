<?php

namespace Faridibin\Laraflags\Tests\Unit\Commands\FeatureGroup;

use Faridibin\Laraflags\Tests\TestCase;
use Faridibin\Laraflags\Models\FeatureGroups;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeactivateFeatureGroupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_deactivate_feature_command_deactivates_a_feature()
    {
        $group = FeatureGroups::factory()->create(['name' => 'test-feature-group']);
        $this->assertTrue($group->isActive());

        $this->artisan('laraflags:deactivate-feature-group', ['name' => 'test-feature-group'])
            ->expectsOutput('The feature group [test-feature-group] was deactivated!')
            ->assertExitCode(0);

        $this->assertFalse($group->fresh()->isActive());
    }

    /** @test */
    public function the_deactivate_feature_command_prompts_for_a_feature_name_if_not_provided()
    {
        $group = FeatureGroups::factory()->create(['name' => 'test-feature-group']);
        $this->assertTrue($group->isActive());

        $this->artisan('laraflags:deactivate-feature-group')
            ->expectsQuestion('Please enter the name of the feature group', 'test-feature-group')
            ->expectsOutput('The feature group [test-feature-group] was deactivated!')
            ->assertExitCode(0);

        $this->assertFalse($group->fresh()->isActive());
    }

    /** @test */
    public function the_deactivate_feature_command_prompts_for_a_confirmation_if_it_does_not_exist()
    {
        $group = FeatureGroups::factory()->create(['name' => 'test-feature-group']);
        $this->assertTrue($group->isActive());

        $this->artisan('laraflags:deactivate-feature-group', ['name' => 'test-feature-does-not-exist'])
            ->expectsQuestion('Please enter the name of the feature group', 'test-feature-group')
            ->expectsOutput('The feature group [test-feature-group] was deactivated!')
            ->assertExitCode(0);

        $this->assertFalse($group->fresh()->isActive());
    }

    /** @test */
    public function the_deactivate_feature_command_does_not_deactivate_a_feature_if_already_deactivated()
    {
        $group = FeatureGroups::factory()->create(['name' => 'test-feature-group']);
        $this->assertTrue($group->isActive());

        $this->artisan('laraflags:deactivate-feature-group', ['name' => 'test-feature-group'])
            ->assertExitCode(0);

        $this->assertFalse($group->wasChanged('active'));
    }
}
