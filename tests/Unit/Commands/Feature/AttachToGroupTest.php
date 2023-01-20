<?php

namespace Faridibin\Laraflags\Tests\Unit\Commands\Feature;

use Faridibin\Laraflags\Tests\TestCase;
use Faridibin\Laraflags\Models\Features;
use Faridibin\Laraflags\Models\FeatureGroups;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttachToGroupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_attach_feature_to_group_command_attaches_a_feature_to_a_group()
    {
        $feature = Features::factory()->create(['name' => 'test-feature']);
        $group = FeatureGroups::factory()->create(['name' => 'test-feature-group']);

        $this->artisan('laraflags:attach-to-group', [
            'feature' => 'test-feature',
            '--group' => 'test-feature-group'
        ])
            ->expectsOutput('The feature [test-feature] was attached to feature group [test-feature-group]!')
            ->assertExitCode(0);

        $this->assertTrue($feature->inGroup($group->name));
    }

    /** @test */
    public function the_attach_feature_to_group_command_prompts_for_a_feature_name_if_not_provided()
    {
        $feature = Features::factory()->create(['name' => 'test-feature']);
        $group = FeatureGroups::factory()->create(['name' => 'test-feature-group']);

        $this->artisan('laraflags:attach-to-group', ['--group' => 'test-feature-group'])
            ->expectsQuestion('Please enter the name of the feature', 'test-feature')
            ->expectsOutput('The feature [test-feature] was attached to feature group [test-feature-group]!')
            ->assertExitCode(0);

        $this->assertTrue($feature->inGroup($group->name));
    }

    /** @test */
    public function the_attach_feature_to_group_command_prompts_for_a_group_name_if_not_provided()
    {
        $feature = Features::factory()->create(['name' => 'test-feature']);
        $group = FeatureGroups::factory()->create(['name' => 'test-feature-group']);

        $this->artisan('laraflags:attach-to-group', ['feature' => 'test-feature'])
            ->expectsQuestion('Please enter the name of the feature group to attach to', 'test-feature-group')
            ->expectsOutput('The feature [test-feature] was attached to feature group [test-feature-group]!')
            ->assertExitCode(0);

        $this->assertTrue($feature->inGroup($group->name));
    }

    /** @test */
    public function the_attach_feature_to_group_command_prompts_for_a_confirmation_to_create_group_if_it_does_not_exist()
    {
        $feature = Features::factory()->create(['name' => 'test-feature']);

        $this->artisan('laraflags:attach-to-group', [
            'feature' => 'test-feature',
            '--group' => 'test-feature-group'
        ])
            ->expectsOutput('A feature group [test-feature-group] does not exist.')
            ->expectsQuestion('Do you want to create a feature group with this name [test-feature-group]?', 'yes')
            ->expectsQuestion('Please enter a description for the feature group', 'This is a description')
            ->expectsChoice('Do you want to activate this feature group?', 'yes', ['no', 'yes'])
            ->expectsOutput('The feature [test-feature] was attached to feature group [test-feature-group]!')
            ->assertExitCode(0);

        $this->assertTrue($feature->inGroup('test-feature-group'));
    }

    /** @test */
    public function the_attach_feature_to_group_does_not_attach_feature_when_group_does_not_exist()
    {
        $feature = Features::factory()->create(['name' => 'test-feature']);

        $this->artisan('laraflags:attach-to-group', [
            'feature' => 'test-feature',
            '--group' => 'test-feature-group'
        ])
            ->expectsQuestion('Do you want to create a feature group with this name [test-feature-group]?', 'no')
            ->assertExitCode(0);

        $this->assertFalse($feature->inGroup('test-feature-group'));
    }
}
