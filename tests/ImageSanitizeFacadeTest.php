<?php

namespace LaravelAt\ImageSanitize\Tests;

use LaravelAt\ImageSanitize\Facades\ImageSanitizeFacade as ImageSanitize;
use PHPUnit\Framework\Attributes\Test;

class ImageSanitizeFacadeTest extends TestCase
{
    #[Test]
    public function it_provides_a_facade(): void
    {
        $content = $this->exploitImageContents();

        $this->assertTrue(ImageSanitize::detect($content));
    }
}
