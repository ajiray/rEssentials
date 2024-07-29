<?php

return [
    App\Providers\AppServiceProvider::class,

    // Add the other core service providers
    App\Providers\AuthServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\RouteServiceProvider::class,

    // Add the Intervention Image Service Provider
    Intervention\Image\Laravel\ServiceProvider::class,
];
