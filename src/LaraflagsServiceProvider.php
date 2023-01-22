<?php

namespace Faridibin\Laraflags;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class LaraflagsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        // Register the main class to use with the facade.
        $this->app->bind('laraflags', function () {
            return new Laraflags();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Load the package's resources.
        $this->loadResources();

        // Register the package's custom Artisan commands
        $this->loadCommands();
    }

    /**
     * Register the package resources such as routes, views, translations, etc.
     *
     * @return void
     */
    protected function loadResources(): void
    {
        // Register database migration paths.
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Register paths to be published by the publish command.
        $this->publishResources();

        // Register routes.
        $this->registerRoutes();

        // Register views.
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laraflags-views');
    }

    /**
     * Register the package's custom Artisan commands.
     *
     * @return void
     */
    protected function loadCommands()
    {
        $commands = [
            \Faridibin\Laraflags\Console\InstallCommand::class,
            \Faridibin\Laraflags\Console\PublishCommand::class,

            // Feature commands
            \Faridibin\Laraflags\Console\Feature\CreateFeature::class,
            \Faridibin\Laraflags\Console\Feature\DeleteFeature::class,
            \Faridibin\Laraflags\Console\Feature\AttachToGroup::class,
            \Faridibin\Laraflags\Console\Feature\DetachFromGroup::class,
            \Faridibin\Laraflags\Console\Feature\ActivateFeature::class,
            \Faridibin\Laraflags\Console\Feature\DeactivateFeature::class,

            // Feature group commands
            \Faridibin\Laraflags\Console\FeatureGroup\CreateFeatureGroup::class,
            \Faridibin\Laraflags\Console\FeatureGroup\DeleteFeatureGroup::class,
            \Faridibin\Laraflags\Console\FeatureGroup\AttachFeature::class,
            \Faridibin\Laraflags\Console\FeatureGroup\DetachFeature::class,
            \Faridibin\Laraflags\Console\FeatureGroup\ActivateFeatureGroup::class,
            \Faridibin\Laraflags\Console\FeatureGroup\DeactivateFeatureGroup::class,

            // Utility commands
            \Faridibin\Laraflags\Console\Utils\ExtendFeatureOrFeatureGroup::class,
            \Faridibin\Laraflags\Console\Utils\ViewFeatureOrFeatureGroup::class,
        ];

        if (config('laraflags.tenant.enabled')) {
            $commands = array_merge($commands, [
                // Tenant commands
                // \Faridibin\Laraflags\Console\Feature\AttachFeatureToTenant::class,
                // \Faridibin\Laraflags\Console\Feature\DetachFeatureFromTenant::class,
                // \Faridibin\Laraflags\Console\FeatureGroup\AttachFeatureGroupToTenant::class,
                // \Faridibin\Laraflags\Console\FeatureGroup\DetachFeatureGroupFromTenant::class,
            ]);
        }

        $this->commands($commands);
    }

    /**
     * Register the paths to be published by the publish command.
     *
     * @return void
     */
    protected function publishResources(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/laraflags.php' => config_path('laraflags.php'),
            ], 'laraflags-config');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/laraflags'),
            ], 'laraflags-views');

            $this->publishes([
                __DIR__ . '/../database/migrations/' => database_path('migrations/laraflags'),
            ], 'laraflags-migrations');
        }
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });
    }

    /**
     * Get the Laraflags route group configuration array.
     *
     * @return array
     */
    protected function routeConfiguration(): array
    {
        return [
            'namespace' => config('laraflags.route.namespace', 'Faridibin\Laraflags\Http\Controllers'),
            'prefix' => config('laraflags.route.prefix', 'laraflags'),
            'middleware' => config('laraflags.route.middleware', 'web'),
        ];
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [];
    }
}
