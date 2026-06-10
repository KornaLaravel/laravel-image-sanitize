<?php

namespace LaravelAt\ImageSanitize;

use Illuminate\Contracts\Config\Repository;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\AutoEncoder;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\EncodedImageInterface;
use LaravelAt\ImageSanitize\Lists\PatternList;

class ImageSanitize
{
    protected ImageManager $imageManager;

    public function __construct(
        protected PatternList $patternList,
        ?ImageManager $imageManager = null,
        protected ?Repository $config = null,
    ) {
        $this->imageManager = $imageManager ?? new ImageManager(
            Driver::class,
            strip: true,
        );
    }

    public function detect(string $content): bool
    {
        foreach ($this->patternList->get() as $forbiddenPattern) {
            if (strpos($content, $forbiddenPattern) !== false) {
                return true;
            }
        }

        return false;
    }

    public function sanitize(string $content): EncodedImageInterface
    {
        $image = $this->imageManager->read($content);

        return $image->encode(new AutoEncoder(
            quality: (int) ($this->config?->get('image-sanitize.quality', 100) ?? 100)
        ));
    }
}
