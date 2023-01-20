<?php

namespace Faridibin\Laraflags\Tests\Unit\Commands\FeatureGroup;

use Faridibin\Laraflags\Facades\Laraflags;
use Faridibin\Laraflags\Tests\TestCase;
use Faridibin\Laraflags\Models\Features;
use Faridibin\Laraflags\Models\FeatureGroups;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateFeatureGroupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_create_feature_group_command_creates_a_new_feature_group()
    {
        $this->artisan('laraflags:create-feature-group', ['name' => 'test-feature-group'])
            ->expectsQuestion('Please enter a description for the feature group', 'This is a description')
            ->expectsChoice('Do you want to activate this feature group?', 'yes', ['no', 'yes'])
            ->expectsOutput('The feature group [test-feature-group] was created!')
            ->assertExitCode(0);

        $this->assertDatabaseHas('feature_groups', [
            'name' => 'test-feature-group',
            'description' => 'This is a description',
            'active' => true
        ]);
    }

    /** @test */
    public function the_create_feature_group_command_prompts_for_a_name_if_not_provided()
    {
        $this->artisan('laraflags:create-feature-group')
            ->expectsQuestion('Please enter a name for the feature group', 'test-feature-group')
            ->expectsQuestion('Please enter a description for the feature group', 'This is a description')
            ->expectsChoice('Do you want to activate this feature group?', 'yes', ['no', 'yes'])
            ->expectsOutput('The feature group [test-feature-group] was created!')
            ->assertExitCode(0);

        $this->assertDatabaseHas('feature_groups', [
            'name' => 'test-feature-group',
            'description' => 'This is a description',
            'active' => true
        ]);
    }

    /** @test */
    public function the_create_feature_group_command_prompts_for_a_name_if_feature_exists()
    {
        FeatureGroups::factory()->create(['name' => 'test-feature-group']);

        $this->artisan('laraflags:create-feature-group', ['name' => 'test-feature-group'])
            ->expectsOutput('A feature group already exists with this name.')
            ->expectsQuestion('Please enter a new name for the feature group', 'test-feature-group-new')
            ->expectsQuestion('Please enter a description for the feature group', 'This is a description')
            ->expectsChoice('Do you want to activate this feature group?', 'yes', ['no', 'yes'])
            ->expectsOutput('The feature group [test-feature-group-new] was created!')
            ->assertExitCode(0);

        $this->assertDatabaseHas('feature_groups', [
            'name' => 'test-feature-group-new',
            'description' => 'This is a description',
            'active' => true
        ]);
    }

    /** @test */
    public function the_create_feature_group_command_creates_a_new_feature_and_attaches_to_group_when_provided()
    {
        $feature = Features::factory()->create(['name' => 'test-feature']);

        $this->artisan('laraflags:create-feature-group', [
            'name' => 'test-feature-group',
            '--feature' => 'test-feature'
        ])
            ->expectsQuestion('Please enter a description for the feature group', 'This is a description')
            ->expectsChoice('Do you want to activate this feature group?', 'yes', ['no', 'yes'])
            ->expectsOutput('The feature group [test-feature-group] was created!')
            ->expectsOutput('The feature [test-feature] was attached to feature group [test-feature-group]!')
            ->assertExitCode(0);

        $group = Laraflags::group('test-feature-group');

        $this->assertDatabaseHas('feature_groups', [
            'name' => 'test-feature-group',
            'description' => 'This is a description',
            'active' => true
        ]);

        $this->assertDatabaseHas('features', [
            'name' => 'test-feature',
            'active' => true
        ]);

        $this->assertTrue($group->hasFeature('test-feature'));
        $this->assertTrue($feature->inGroup('test-feature-group'));
    }

    /** @test */
    public function the_create_feature_group_command_creates_a_new_feature_and_attaches_to_new_group_when_provided()
    {
        $this->artisan('laraflags:create-feature-group', [
            'name' => 'test-feature-group',
            '--feature' => 'test-feature'
        ])
            ->expectsQuestion('Please enter a description for the feature group', 'This is a description')
            ->expectsChoice('Do you want to activate this feature group?', 'yes', ['no', 'yes'])
            ->expectsOutput('The feature group [test-feature-group] was created!')
            ->expectsOutput('A feature [test-feature] does not exist.')
            ->expectsChoice('Do you want to create a new feature with this name [test-feature]?', 'yes', ['no', 'yes'])
            ->expectsQuestion('Please enter a description for the feature', 'This is a description')
            ->expectsChoice('Do you want to activate this feature?', 'yes', ['no', 'yes'])
            ->expectsOutput('The feature [test-feature] was attached to feature group [test-feature-group]!')
            ->assertExitCode(0);

        $group = Laraflags::group('test-feature-group');
        $feature = Laraflags::feature('test-feature');

        $this->assertDatabaseHas('feature_groups', [
            'name' => 'test-feature-group',
            'description' => 'This is a description',
            'active' => true
        ]);

        $this->assertDatabaseHas('features', [
            'name' => 'test-feature',
            'active' => true
        ]);

        $this->assertTrue($group->hasFeature('test-feature'));
        $this->assertTrue($feature->inGroup('test-feature-group'));
    }
}
