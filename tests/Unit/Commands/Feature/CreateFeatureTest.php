<?php

namespace Faridibin\Laraflags\Tests\Unit\Commands\Feature;

use Faridibin\Laraflags\Facades\Laraflags;
use Faridibin\Laraflags\Tests\TestCase;
use Faridibin\Laraflags\Models\Features;
use Faridibin\Laraflags\Models\FeatureGroups;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_create_feature_command_creates_a_new_feature()
    {
        $this->artisan('laraflags:create-feature', ['name' => 'test-feature'])
            ->expectsQuestion('Please enter a description for the feature', 'This is a description')
            ->expectsChoice('Do you want to activate this feature?', 'yes', ['no', 'yes'])
            ->expectsOutput('The feature [test-feature] was created!')
            ->assertExitCode(0);

        $this->assertDatabaseHas('features', [
            'name' => 'test-feature',
            'description' => 'This is a description',
            'active' => true
        ]);
    }

    /** @test */
    public function the_create_feature_command_prompts_for_a_name_if_not_provided()
    {
        $this->artisan('laraflags:create-feature')
            ->expectsQuestion('Please enter a name for the feature', 'test-feature')
            ->expectsQuestion('Please enter a description for the feature', 'This is a description')
            ->expectsChoice('Do you want to activate this feature?', 'yes', ['no', 'yes'])
            ->expectsOutput('The feature [test-feature] was created!')
            ->assertExitCode(0);

        $this->assertDatabaseHas('features', [
            'name' => 'test-feature',
            'description' => 'This is a description',
            'active' => true
        ]);
    }

    /** @test */
    public function the_create_feature_command_prompts_for_a_name_if_feature_exists()
    {
        Features::factory()->create(['name' => 'test-feature']);

        $this->artisan('laraflags:create-feature', ['name' => 'test-feature'])
            ->expectsOutput('A feature already exists with this name.')
            ->expectsQuestion('Please enter a new name for the feature', 'test-feature-new')
            ->expectsQuestion('Please enter a description for the feature', 'This is a description')
            ->expectsChoice('Do you want to activate this feature?', 'yes', ['no', 'yes'])
            ->expectsOutput('The feature [test-feature-new] was created!')
            ->assertExitCode(0);

        $this->assertDatabaseHas('features', [
            'name' => 'test-feature-new',
            'description' => 'This is a description',
            'active' => true
        ]);
    }

    /** @test */
    public function the_create_feature_command_creates_a_new_feature_and_attaches_to_group_when_provided()
    {
        $group = FeatureGroups::factory()->create(['name' => 'test-feature-group']);

        $this->artisan('laraflags:create-feature', [
            'name' => 'test-feature',
            '--group' => 'test-feature-group'
        ])
            ->expectsQuestion('Please enter a description for the feature', 'This is a description')
            ->expectsChoice('Do you want to activate this feature?', 'yes', ['no', 'yes'])
            ->expectsOutput('The feature [test-feature] was created!')
            ->expectsOutput('The feature [test-feature] was attached to feature group [test-feature-group]!')
            ->assertExitCode(0);

        $this->assertDatabaseHas('features', [
            'name' => 'test-feature',
            'description' => 'This is a description',
            'active' => true
        ]);

        $this->assertDatabaseHas('feature_groups', [
            'name' => 'test-feature-group',
            'active' => true
        ]);

        $this->assertTrue($group->hasFeature('test-feature'));
    }

    /** @test */
    public function the_create_feature_command_creates_a_new_feature_and_attaches_to_new_group_when_provided()
    {
        $this->artisan('laraflags:create-feature', [
            'name' => 'test-feature',
            '--group' => 'test-feature-group'
        ])
            ->expectsQuestion('Please enter a description for the feature', 'This is a description')
            ->expectsChoice('Do you want to activate this feature?', 'yes', ['no', 'yes'])
            ->expectsOutput('The feature [test-feature] was created!')
            ->expectsOutput('A feature group [test-feature-group] does not exist.')
            ->expectsChoice('Do you want to create a new feature group with this name [test-feature-group]?', 'yes', ['no', 'yes'])
            ->expectsQuestion('Please enter a description for the feature group', 'This is a description')
            ->expectsChoice('Do you want to activate this feature group?', 'yes', ['no', 'yes'])
            ->expectsOutput('The feature [test-feature] was attached to feature group [test-feature-group]!')
            ->assertExitCode(0);

        $this->assertDatabaseHas('features', [
            'name' => 'test-feature',
            'description' => 'This is a description',
            'active' => true
        ]);

        $this->assertDatabaseHas('feature_groups', [
            'name' => 'test-feature-group',
            'active' => true
        ]);

        $feature = Laraflags::feature('test-feature');

        $this->assertTrue($feature->inGroup('test-feature-group'));
    }
}
