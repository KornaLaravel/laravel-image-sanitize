<?php

namespace LaravelAt\ImageSanitize\ServiceProviders;

use Illuminate\Support\ServiceProvider;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use LaravelAt\ImageSanitize\ImageSanitize;
use LaravelAt\ImageSanitize\RequestHandler;

class ImageSanitizeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/image-sanitize.php' => config_path('image-sanitize.php'),
        ], 'image-sanitize-config');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/image-sanitize.php', 'image-sanitize');

        $this->app->singletonIf(ImageManager::class, function ($app): ImageManager {
            return new ImageManager(
                $app['config']->get('image-sanitize.driver', Driver::class),
                autoOrientation: $app['config']->get('image-sanitize.auto_orientation', true),
                decodeAnimation: $app['config']->get('image-sanitize.decode_animation', true),
                strip: $app['config']->get('image-sanitize.strip_metadata', true),
            );
        });

        $this->app->singleton(ImageSanitize::class);
        $this->app->singleton(RequestHandler::class);
    }
}
