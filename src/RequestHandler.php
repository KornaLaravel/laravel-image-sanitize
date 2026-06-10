<?php

namespace LaravelAt\ImageSanitize;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use LaravelAt\ImageSanitize\Lists\MimeTypeList;

class RequestHandler
{
    public function __construct(
        protected ImageSanitize $imageSanitize,
        protected MimeTypeList $mimeTypeList,
    ) {}

    public function handle(Request $request): void
    {
        foreach ($this->getMaliciousImages($request->allFiles()) as $file) {
            file_put_contents($file->getPathname(), (string) $this->imageSanitize->sanitize($this->fileContents($file)));
        }
    }

    /**
     * @param  array<array-key, mixed>  $files
     * @return array<array-key, UploadedFile>
     */
    public function getMaliciousImages(array $files): array
    {
        return array_filter($this->getImages($files), function (UploadedFile $file) {
            return $this->imageSanitize->detect($this->fileContents($file));
        });
    }

    /**
     * @param  array<array-key, mixed>  $files
     * @return array<array-key, UploadedFile>
     */
    public function getImages(array $files): array
    {
        return array_filter($this->collectUploadedFiles($files), function (UploadedFile $file) {
            return in_array($file->getMimeType(), $this->mimeTypeList->get(), true);
        });
    }

    /**
     * @param  array<array-key, mixed>  $files
     * @return array<array-key, UploadedFile>
     */
    protected function collectUploadedFiles(array $files, bool $preserveKeys = true): array
    {
        $uploadedFiles = [];

        foreach ($files as $key => $file) {
            if ($file instanceof UploadedFile) {
                if ($preserveKeys && is_string($key)) {
                    $uploadedFiles[$key] = $file;

                    continue;
                }

                $uploadedFiles[] = $file;

                continue;
            }

            if (! is_array($file)) {
                continue;
            }

            foreach ($this->collectUploadedFiles($file, false) as $nestedFile) {
                $uploadedFiles[] = $nestedFile;
            }
        }

        return $uploadedFiles;
    }

    protected function fileContents(UploadedFile $file): string
    {
        $contents = $file->get();

        if ($contents === false) {
            return '';
        }

        return $contents;
    }
}
