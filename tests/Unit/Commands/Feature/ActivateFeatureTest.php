<?php

namespace Faridibin\Laraflags\Tests\Unit\Commands\Feature;

use Faridibin\Laraflags\Tests\TestCase;
use Faridibin\Laraflags\Models\Features;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivateFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_activate_feature_command_activates_a_feature()
    {
        $feature = Features::factory()->inactive()->create(['name' => 'test-feature']);
        $this->assertFalse($feature->isActive());

        $this->artisan('laraflags:activate-feature', ['feature' => 'test-feature'])
            ->expectsOutput('The feature [test-feature] was activated!')
            ->assertExitCode(0);

        $this->assertTrue($feature->fresh()->isActive());
    }

    /** @test */
    public function the_activate_feature_command_prompts_for_a_feature_name_if_not_provided()
    {
        $feature = Features::factory()->inactive()->create(['name' => 'test-feature']);
        $this->assertFalse($feature->isActive());

        $this->artisan('laraflags:activate-feature')
            ->expectsQuestion('Please enter the name of the feature', 'test-feature')
            ->expectsOutput('The feature [test-feature] was activated!')
            ->assertExitCode(0);

        $this->assertTrue($feature->fresh()->isActive());
    }

    /** @test */
    public function the_activate_feature_command_prompts_for_a_confirmation_if_it_does_not_exist()
    {
        $feature = Features::factory()->inactive()->create(['name' => 'test-feature']);
        $this->assertFalse($feature->isActive());

        $this->artisan('laraflags:activate-feature', ['feature' => 'test-feature-does-not-exist'])
            ->expectsQuestion('Please enter the name of the feature', 'test-feature')
            ->expectsOutput('The feature [test-feature] was activated!')
            ->assertExitCode(0);

        $this->assertTrue($feature->fresh()->isActive());
    }

    /** @test */
    public function the_activate_feature_command_does_not_activate_a_feature_if_already_activated()
    {
        $feature = Features::factory()->create(['name' => 'test-feature']);
        $this->assertTrue($feature->isActive());

        $this->artisan('laraflags:activate-feature', ['feature' => 'test-feature'])
            ->assertExitCode(0);

        $this->assertFalse($feature->wasChanged('active'));
    }
}
