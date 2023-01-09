<?php

namespace Faridibin\Laraflags\Tests\Unit\Commands;

use Faridibin\Laraflags\Tests\TestCase;
use Faridibin\Laraflags\Facades\Laraflags;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class InstallLaraflagsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_install_command_copies_the_configuration_and_views()
    {
        // make sure we're starting from a clean state
        if (Laraflags::configExists()) {
            unlink(config_path('laraflags.php'));
        }

        if (Laraflags::viewsExist()) {
            File::deleteDirectory(resource_path('views/vendor/laraflags'));
        }

        $this->assertFalse(Laraflags::configExists());
        $this->assertFalse(Laraflags::viewsExist());

        Artisan::call('laraflags:install');

        $this->assertTrue(Laraflags::configExists());
        $this->assertTrue(Laraflags::viewsExist());
    }
}
