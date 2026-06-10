![Laravel Image Sanitize logo](https://raw.githubusercontent.com/laravel-at/laravel-image-sanitize/master/art/logo.png)

# It prevents malicious code execution!

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-at/laravel-image-sanitize.svg?style=flat-square)](https://packagist.org/packages/laravel-at/laravel-image-sanitize)
[![GitHub Tests Action Status](https://github.com/laravel-at/laravel-image-sanitize/workflows/tests/badge.svg)](https://github.com/laravel-at/laravel-image-sanitize/actions)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-at/laravel-image-sanitize.svg?style=flat-square)](https://packagist.org/packages/laravel-at/laravel-image-sanitize)

This is a small but handy package to prevent malicious code execution coming into your application through uploaded images.
It was created after being inspired by [@appelsiini](https://github.com/appelsiini)'s [talk on How to Hack your Laravel Application](https://speakerdeck.com/anamus/how-your-laravel-application-can-get-hacked-f7acca32-3721-4c06-9a2e-5965cd9a4a29)

## Installation

This version requires PHP 8.3+, Laravel 12 or 13, and Intervention Image 4.

You can install the package via composer:

```bash
composer require laravel-at/laravel-image-sanitize
```

## Usage

Apply the middleware to routes that receive image uploads:

```php
use App\Http\Controllers\FileController;
use LaravelAt\ImageSanitize\ImageSanitizeMiddleware;

Route::post('/files', [FileController::class, 'upload'])
    ->name('file.upload')
    ->middleware(ImageSanitizeMiddleware::class);
```

If you prefer a middleware alias, register it in your application's `bootstrap/app.php` file:

```php
use Illuminate\Foundation\Configuration\Middleware;
use LaravelAt\ImageSanitize\ImageSanitizeMiddleware;

->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
        'image-sanitize' => ImageSanitizeMiddleware::class,
    ]);
})
```

Then use the alias on your upload routes:

```php
Route::post('/files', [FileController::class, 'upload'])
    ->name('file.upload')
    ->middleware('image-sanitize');
```

If you want to learn more about middlewares, please check out the [official Laravel documentation](https://laravel.com/docs/13.x/middleware).

## Configuration

You may publish the configuration file:

```bash
php artisan vendor:publish --tag=image-sanitize-config
```

The default configuration scans JPEG, PNG, GIF, BMP, and WebP uploads for suspicious byte patterns, then re-encodes matching images through Intervention Image. SVG files are not supported by default.

```php
return [
    'allowed_mime_types' => [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/bmp',
        'image/webp',
    ],

    'patterns' => [
        '<?php',
        'phar',
    ],

    'driver' => \Intervention\Image\Drivers\Gd\Driver::class,
    'quality' => 100,
    'auto_orientation' => true,
    'decode_animation' => true,
    'strip_metadata' => true,
];
```

You can also use the facade directly:

```php
if (ImageSanitize::detect($contents)) {
    $contents = (string) ImageSanitize::sanitize($contents);
}
```

### Testing

``` bash
composer test
```

Run the full local quality check:

```bash
composer check
```

Or run the individual checks:

```bash
composer format-test
composer analyse
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email adrian@nuernberger.me instead of using the issue tracker.

## Credits

- [Adrian Nürnberger](https://github.com/nuernbergerA)
- [Mathias Onea](https://mathiasonea.com)
- Logo by [Caneco](https://github.com/caneco)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
