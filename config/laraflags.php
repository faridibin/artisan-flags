<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Laraflags Route Configuration
    |--------------------------------------------------------------------------
    */
    'route' => [
        /*
        |--------------------------------------------------------------------------
        | Laraflags Route Prefix
        |--------------------------------------------------------------------------
        |
        | This option defines the route prefix for Laraflags.
        | Default: 'laraflags'
        |
        */
        'prefix' => 'laraflags',

        /*
        |--------------------------------------------------------------------------
        | Laraflags Route Namespace
        |--------------------------------------------------------------------------
        |
        | This option defines the route namespace for Laraflags.
        | Default: 'Faridibin\Laraflags\Http\Controllers'
        |
        */
        'namespace' => 'Faridibin\Laraflags\Http\Controllers',

        /*
        |--------------------------------------------------------------------------
        | Laraflags Route Middleware
        |--------------------------------------------------------------------------
        |
        | This option defines the middleware for Laraflags.
        | Default: ['web']
        |
        */
        'middleware' => ['web']
    ],

    /*
    |--------------------------------------------------------------------------
    | Tenant Configuration
    |--------------------------------------------------------------------------
    */
    'tenancy' => [
        /*
        |--------------------------------------------------------------------------
        | Enable Tenancy for Feature Flags
        |--------------------------------------------------------------------------
        |
        | This option enables the tenancy for feature flags.
        | If this is set to true, you can set a tenant_id for each
        | feature and feature-group. When the tenant_id is set,
        | the feature or feature-group will be only available for the tenant.
        |
        */
        'enabled' => false,

        /*
        |--------------------------------------------------------------------------
        | Tenant Model
        |--------------------------------------------------------------------------
        |
        | This option defines the tenant model.
        | Deafult: \App\Models\Tenant::class
        |
        */
        'model' => \App\Models\Tenant::class,

        /*
        |--------------------------------------------------------------------------
        | Tenant Identifier
        |--------------------------------------------------------------------------
        |
        | This option defines the tenant identifier.
        |
        */
        'identifier' => 'id'
    ],

    /*
    |--------------------------------------------------------------------------
    | Expiration of Features and feature-groups
    |--------------------------------------------------------------------------
    */
    'expiration' => [
        /*
        |--------------------------------------------------------------------------
        | Enable Expiration for Features and feature-groups
        |--------------------------------------------------------------------------
        |
        | This option enables the expiration of features and feature-groups.
        | If this is set to true, you can set an expiration date for each
        | feature and feature-group. When the expiration date is reached,
        | the feature or feature-group will be deactivated.
        |
        */
        'enabled' => false,

        /*
        |--------------------------------------------------------------------------
        | Environment for Expiration of Features and feature-groups
        |--------------------------------------------------------------------------
        |
        */
        'environment' => ['staging', 'production']
    ]
];
