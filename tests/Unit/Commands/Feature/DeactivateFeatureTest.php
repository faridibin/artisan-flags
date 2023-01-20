<?php

namespace Faridibin\Laraflags\Tests\Unit\Commands\Feature;

use Faridibin\Laraflags\Tests\TestCase;
use Faridibin\Laraflags\Models\Features;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeactivateFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_deactivate_feature_command_deactivates_a_feature()
    {
        $feature = Features::factory()->create(['name' => 'test-feature']);
        $this->assertTrue($feature->isActive());

        $this->artisan('laraflags:deactivate-feature', ['name' => 'test-feature'])
            ->expectsOutput('The feature [test-feature] was deactivated!')
            ->assertExitCode(0);

        $this->assertFalse($feature->fresh()->isActive());
    }

    /** @test */
    public function the_deactivate_feature_command_prompts_for_a_feature_name_if_not_provided()
    {
        $feature = Features::factory()->create(['name' => 'test-feature']);
        $this->assertTrue($feature->isActive());

        $this->artisan('laraflags:deactivate-feature')
            ->expectsQuestion('Please enter the name of the feature', 'test-feature')
            ->expectsOutput('The feature [test-feature] was deactivated!')
            ->assertExitCode(0);

        $this->assertFalse($feature->fresh()->isActive());
    }

    /** @test */
    public function the_deactivate_feature_command_prompts_for_a_confirmation_if_it_does_not_exist()
    {
        $feature = Features::factory()->create(['name' => 'test-feature']);
        $this->assertTrue($feature->isActive());

        $this->artisan('laraflags:deactivate-feature', ['name' => 'test-feature-does-not-exist'])
            ->expectsQuestion('Please enter the name of the feature', 'test-feature')
            ->expectsOutput('The feature [test-feature] was deactivated!')
            ->assertExitCode(0);

        $this->assertFalse($feature->fresh()->isActive());
    }

    /** @test */
    public function the_deactivate_feature_command_does_not_deactivate_a_feature_if_already_deactivated()
    {
        $feature = Features::factory()->create(['name' => 'test-feature']);
        $this->assertTrue($feature->isActive());

        $this->artisan('laraflags:deactivate-feature', ['name' => 'test-feature'])
            ->assertExitCode(0);

        $this->assertFalse($feature->wasChanged('active'));
    }
}
