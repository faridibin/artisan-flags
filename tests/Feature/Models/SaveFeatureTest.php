<?php

namespace Faridibin\Laraflags\Tests\Feature\Models;

use Faridibin\Laraflags\Tests\TestCase;
use Faridibin\Laraflags\Models\Features;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SaveFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_save_a_feature()
    {
        $feature = Features::factory()->create();

        $this->assertDatabaseHas('features', [
            'id' => $feature->id,
        ]);
    }

    /** @test */
    public function it_can_save_a_feature_with_a_name()
    {
        $feature = Features::factory()->create([
            'name' => 'test',
        ]);

        $this->assertDatabaseHas('features', [
            'id' => $feature->id,
            'name' => 'test',
        ]);
    }

    /** @test */
    public function it_can_save_a_feature_with_a_description()
    {
        $feature = Features::factory()->create([
            'description' => 'test',
        ]);

        $this->assertDatabaseHas('features', [
            'id' => $feature->id,
            'description' => 'test',
        ]);
    }

    /** @test */
    public function it_can_save_a_feature_with_a_active()
    {
        $feature = Features::factory()->create([
            'active' => false,
        ]);

        $this->assertDatabaseHas('features', [
            'id' => $feature->id,
            'active' => false,
        ]);
    }

    /** @test */
    public function it_can_save_a_feature_with_an_expires_at()
    {
        $feature = Features::factory()->create([
            'expires_at' => now()->addDays(1),
        ]);

        $this->assertDatabaseHas('features', [
            'id' => $feature->id,
            'expires_at' => now()->addDays(1),
        ]);
    }
}
