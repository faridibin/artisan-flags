<?php

namespace Faridibin\Laraflags\Tests;

use Faridibin\Laraflags\LaraflagsServiceProvider;
use Illuminate\Foundation\Testing\WithFaker;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use WithFaker;

    /**
     * Setup the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->withFactories(__DIR__ . '/database/factories');
    }

    /**
     * Define package service providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            LaraflagsServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing_db');
        $app['config']->set('database.connections.testing_db', [
            'driver' => 'sqlite',
            'database' => ':memory:'
        ]);
    }
}
