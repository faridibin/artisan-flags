<?php

namespace Faridibin\Laraflags\Tests\Unit\Commands\FeatureGroup;

use Faridibin\Laraflags\Facades\Laraflags;
use Faridibin\Laraflags\Tests\TestCase;
use Faridibin\Laraflags\Models\FeatureGroups;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteFeatureGroupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_delete_feature_group_command_deletes_a_feature()
    {
        FeatureGroups::factory()->create(['name' => 'test-feature-group']);

        $this->artisan('laraflags:delete-feature-group', ['name' => 'test-feature-group'])
            ->expectsOutput('The feature group [test-feature-group] was deleted!')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('features', [
            'name' => 'test-feature-group',
        ]);

        $this->assertFalse(Laraflags::group('test-feature-group'));
    }

    /** @test */
    public function the_delete_feature_group_command_prompts_for_a_name_if_not_provided()
    {
        FeatureGroups::factory()->create(['name' => 'test-feature-group']);

        $this->artisan('laraflags:delete-feature-group')
            ->expectsQuestion('Please enter the name of the feature group', 'test-feature-group')
            ->expectsOutput('The feature group [test-feature-group] was deleted!')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('features', [
            'name' => 'test-feature-group',
        ]);

        $this->assertFalse(Laraflags::group('test-feature-group'));
    }

    /** @test */
    public function the_delete_feature_group_command_prompts_for_a_name_if_feature_group_does_not_exist()
    {
        FeatureGroups::factory()->create(['name' => 'test-feature-group']);

        $this->artisan('laraflags:delete-feature-group', ['name' => 'test-feature-new'])
            ->expectsQuestion('Please enter the name of the feature group', 'test-feature-group')
            ->expectsOutput('The feature group [test-feature-group] was deleted!')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('features', [
            'name' => 'test-feature-group',
        ]);

        $this->assertFalse(Laraflags::group('test-feature-group'));
    }
}
