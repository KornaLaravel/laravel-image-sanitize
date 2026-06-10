<?php

namespace LaravelAt\ImageSanitize\Tests;

use LaravelAt\ImageSanitize\Facades\ImageSanitizeFacade;
use LaravelAt\ImageSanitize\ServiceProviders\ImageSanitizeServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app): array
    {
        return [ImageSanitizeServiceProvider::class];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'ImageSanitize' => ImageSanitizeFacade::class,
        ];
    }

    protected function exploitImageContents(): string
    {
        $contents = file_get_contents(__DIR__.'/stubs/exploit.jpeg');

        if ($contents === false) {
            $this->fail('The exploit.jpeg test fixture could not be read.');
        }

        return $contents;
    }
}
