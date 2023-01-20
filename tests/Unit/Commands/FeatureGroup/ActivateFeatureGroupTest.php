<?php

namespace Faridibin\Laraflags\Tests\Unit\Commands\FeatureGroup;

use Faridibin\Laraflags\Tests\TestCase;
use Faridibin\Laraflags\Models\FeatureGroups;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivateFeatureGroupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_activate_feature_command_activates_a_feature()
    {
        $group = FeatureGroups::factory()->inactive()->create(['name' => 'test-feature-group']);
        $this->assertFalse($group->isActive());

        $this->artisan('laraflags:activate-feature-group', ['name' => 'test-feature-group'])
            ->expectsOutput('The feature group [test-feature-group] was activated!')
            ->assertExitCode(0);

        $this->assertTrue($group->fresh()->isActive());
    }

    /** @test */
    public function the_activate_feature_command_prompts_for_a_feature_name_if_not_provided()
    {
        $group = FeatureGroups::factory()->inactive()->create(['name' => 'test-feature-group']);
        $this->assertFalse($group->isActive());

        $this->artisan('laraflags:activate-feature-group')
            ->expectsQuestion('Please enter the name of the feature group', 'test-feature-group')
            ->expectsOutput('The feature group [test-feature-group] was activated!')
            ->assertExitCode(0);

        $this->assertTrue($group->fresh()->isActive());
    }

    /** @test */
    public function the_activate_feature_command_prompts_for_a_confirmation_if_it_does_not_exist()
    {
        $group = FeatureGroups::factory()->inactive()->create(['name' => 'test-feature-group']);
        $this->assertFalse($group->isActive());

        $this->artisan('laraflags:activate-feature-group', ['name' => 'test-feature-group-does-not-exist'])
            ->expectsQuestion('Please enter the name of the feature group', 'test-feature-group')
            ->expectsOutput('The feature group [test-feature-group] was activated!')
            ->assertExitCode(0);

        $this->assertTrue($group->fresh()->isActive());
    }

    /** @test */
    public function the_activate_feature_command_does_not_activate_a_feature_if_already_activated()
    {
        $group = FeatureGroups::factory()->create(['name' => 'test-feature-group']);
        $this->assertTrue($group->isActive());

        $this->artisan('laraflags:activate-feature-group', ['name' => 'test-feature-group'])
            ->assertExitCode(0);

        $this->assertFalse($group->wasChanged('active'));
    }
}
