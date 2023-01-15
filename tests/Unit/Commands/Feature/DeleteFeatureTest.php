<?php

namespace Faridibin\Laraflags\Tests\Unit\Commands\Feature;

use Faridibin\Laraflags\Facades\Laraflags;
use Faridibin\Laraflags\Tests\TestCase;
use Faridibin\Laraflags\Models\Features;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_delete_feature_command_deletes_a_feature()
    {
        Features::factory()->create(['name' => 'test-feature']);

        $this->artisan('laraflags:delete-feature', ['feature' => 'test-feature'])
            ->expectsOutput('The feature [test-feature] was deleted!')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('features', [
            'name' => 'test-feature',
        ]);
    }

    /** @test */
    public function the_delete_feature_command_prompts_for_a_name_if_not_provided()
    {
        Features::factory()->create(['name' => 'test-feature']);

        $this->artisan('laraflags:delete-feature')
            ->expectsQuestion('Please enter the name of the feature', 'test-feature')
            ->expectsOutput('The feature [test-feature] was deleted!')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('features', [
            'name' => 'test-feature',
        ]);
    }

    /** @test */
    public function the_delete_feature_command_prompts_for_a_name_if_feature_does_not_exist()
    {
        Features::factory()->create(['name' => 'test-feature']);

        $this->artisan('laraflags:delete-feature', ['feature' => 'test-feature-new'])
            ->expectsQuestion('Please enter the name of the feature', 'test-feature')
            ->expectsOutput('The feature [test-feature] was deleted!')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('features', [
            'name' => 'test-feature',
        ]);
    }
}
