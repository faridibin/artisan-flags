<?php

namespace Faridibin\Laraflags\Tests\Unit\Commands\Utils;

use Faridibin\Laraflags\Tests\TestCase;
use Faridibin\Laraflags\Models\Features;
use Faridibin\Laraflags\Models\FeatureGroups;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewFeatureOrFeatureGroupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_view_feature_or_feature_group_command_views_a_feature()
    {
        Features::factory(10)->create();

        $this->artisan('laraflags:view', [
            'what' => 'feature'
        ])
            ->expectsTable(
                [
                    'Name',
                    'Description',
                    'Activated',
                    'Expires At'
                ],
                Features::all(['name', 'description', 'active', 'expires_at'])->toArray()
            )
            ->assertExitCode(0);
    }

    /** @test */
    public function the_view_feature_or_feature_group_command_views_a_feature_group()
    {
        FeatureGroups::factory(10)->create();

        $this->artisan('laraflags:view', [
            'what' => 'feature-group'
        ])
            ->expectsTable(
                [
                    'Name',
                    'Description',
                    'Activated',
                    'Expires At'
                ],
                FeatureGroups::all(['name', 'description', 'active', 'expires_at'])->toArray()
            )
            ->assertExitCode(0);
    }

    /** @test */
    public function the_view_feature_or_feature_group_command_shows_all_if_option_not_provided()
    {
        Features::factory(10)->create();
        FeatureGroups::factory(10)->create();

        $this->artisan('laraflags:view')
            ->expectsTable(
                [
                    'Name',
                    'Description',
                    'Activated',
                    'Expires At'
                ],
                Features::all(['name', 'description', 'active', 'expires_at'])->toArray()
            )
            ->expectsTable(
                [
                    'Name',
                    'Description',
                    'Activated',
                    'Expires At'
                ],
                FeatureGroups::all(['name', 'description', 'active', 'expires_at'])->toArray()
            )
            ->assertExitCode(0);
    }
}
