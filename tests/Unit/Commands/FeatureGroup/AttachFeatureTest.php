<?php

namespace Faridibin\Laraflags\Tests\Unit\Commands\FeatureGroup;

use Faridibin\Laraflags\Tests\TestCase;
use Faridibin\Laraflags\Models\Features;
use Faridibin\Laraflags\Models\FeatureGroups;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttachFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_attach_feature_to_group_command_attaches_a_feature_to_a_group()
    {
        $group = FeatureGroups::factory()->create(['name' => 'test-feature-group']);
        $feature = Features::factory()->create(['name' => 'test-feature']);

        $this->artisan('laraflags:attach-feature', [
            'group' => 'test-feature-group',
            '--feature' => 'test-feature'
        ])
            ->expectsOutput('The feature [test-feature] was attached to feature group [test-feature-group]!')
            ->assertExitCode(0);

        $this->assertTrue($group->hasFeature($feature->name));
    }

    /** @test */
    public function the_attach_feature_to_group_command_prompts_for_a_feature_group_name_if_not_provided()
    {
        $group = FeatureGroups::factory()->create(['name' => 'test-feature-group']);
        $feature = Features::factory()->create(['name' => 'test-feature']);

        $this->artisan('laraflags:attach-feature', ['--feature' => 'test-feature'])
            ->expectsQuestion('Please enter the name of the feature group', 'test-feature-group')
            ->expectsOutput('The feature [test-feature] was attached to feature group [test-feature-group]!')
            ->assertExitCode(0);

        $this->assertTrue($group->hasFeature($feature->name));
    }

    /** @test */
    public function the_attach_feature_to_group_command_prompts_for_a_feature_name_if_not_provided()
    {
        $group = FeatureGroups::factory()->create(['name' => 'test-feature-group']);
        $feature = Features::factory()->create(['name' => 'test-feature']);

        $this->artisan('laraflags:attach-feature', ['group' => 'test-feature-group'])
            ->expectsQuestion('Please enter the name of the feature to attach', 'test-feature')
            ->expectsOutput('The feature [test-feature] was attached to feature group [test-feature-group]!')
            ->assertExitCode(0);

        $this->assertTrue($group->hasFeature($feature->name));
    }

    /** @test */
    public function the_attach_feature_to_group_command_prompts_for_a_confirmation_to_create_group_if_it_does_not_exist()
    {
        $group = FeatureGroups::factory()->create(['name' => 'test-feature-group']);

        $this->artisan('laraflags:attach-feature', [
            'group' => 'test-feature-group',
            '--feature' => 'test-feature',
        ])
            ->expectsOutput('A feature [test-feature] does not exist.')
            ->expectsQuestion('Do you want to create a feature with this name [test-feature]?', 'yes')
            ->expectsQuestion('Please enter a description for the feature', 'This is a description')
            ->expectsChoice('Do you want to activate this feature?', 'yes', ['no', 'yes'])
            ->expectsOutput('The feature [test-feature] was attached to feature group [test-feature-group]!')
            ->assertExitCode(0);

        $this->assertTrue($group->hasFeature('test-feature'));
    }

    /** @test */
    public function the_attach_feature_to_group_does_not_attach_feature_when_group_does_not_exist()
    {
        $group = FeatureGroups::factory()->create(['name' => 'test-feature-group']);

        $this->artisan('laraflags:attach-feature', [
            'group' => 'test-feature-group',
            '--feature' => 'test-feature'
        ])
            ->expectsQuestion('Do you want to create a feature with this name [test-feature]?', 'no')
            ->assertExitCode(0);

        $this->assertFalse($group->hasFeature('test-feature'));
    }
}
