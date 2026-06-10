<?php

namespace LaravelAt\ImageSanitize\Lists;

use Illuminate\Contracts\Config\Repository;

class MimeTypeList
{
    /** @var array<int, string> */
    protected const DEFAULT_MIME_TYPES = [
        'image/jpeg',
        'image/gif',
        'image/png',
        'image/bmp',
        'image/webp',
    ];

    public function __construct(
        protected ?Repository $config = null,
    ) {}

    /**
     * @return array<int, string>
     */
    public function get(): array
    {
        $mimeTypes = $this->config?->get('image-sanitize.allowed_mime_types', self::DEFAULT_MIME_TYPES);

        if (! is_array($mimeTypes)) {
            return self::DEFAULT_MIME_TYPES;
        }

        return array_values(array_filter($mimeTypes, is_string(...)));
    }
}
