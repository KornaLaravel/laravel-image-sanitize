<?php

namespace LaravelAt\ImageSanitize\Tests;

use LaravelAt\ImageSanitize\ImageSanitize;
use PHPUnit\Framework\Attributes\Test;

class ImageSanitizeTest extends TestCase
{
    #[Test]
    public function it_detects_embedded_malicious_code(): void
    {
        $content = $this->exploitImageContents();

        $this->assertTrue(
            $this->app->make(ImageSanitize::class)->detect($content)
        );
    }

    #[Test]
    public function it_uses_configured_detection_patterns(): void
    {
        $this->app['config']->set('image-sanitize.patterns', ['custom-payload']);

        $this->assertTrue(
            $this->app->make(ImageSanitize::class)->detect('clean-prefix custom-payload clean-suffix')
        );
    }

    #[Test]
    public function it_merges_default_configuration(): void
    {
        $patterns = config('image-sanitize.patterns');
        $allowedMimeTypes = config('image-sanitize.allowed_mime_types');

        $this->assertIsArray($patterns);
        $this->assertIsArray($allowedMimeTypes);
        $this->assertContains('<?php', $patterns);
        $this->assertContains('image/webp', $allowedMimeTypes);
    }

    #[Test]
    public function it_removes_malicious_code(): void
    {
        $content = $this->exploitImageContents();

        $secureImage = $this->app->make(ImageSanitize::class)->sanitize($content);

        $this->assertFalse($this->app->make(ImageSanitize::class)->detect($secureImage));
    }
}
