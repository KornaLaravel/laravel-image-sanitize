<?php

namespace LaravelAt\ImageSanitize\Facades;

use Illuminate\Support\Facades\Facade;
use Intervention\Image\Interfaces\EncodedImageInterface;
use LaravelAt\ImageSanitize\ImageSanitize;

/**
 * @method static bool detect(string $content)
 * @method static EncodedImageInterface sanitize(string $content)
 *
 * @see ImageSanitize
 */
class ImageSanitizeFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ImageSanitize::class;
    }
}
