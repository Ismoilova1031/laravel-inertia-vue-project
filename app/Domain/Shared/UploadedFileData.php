<?php

namespace App\Domain\Shared;

final class UploadedFileData
{
    public function __construct(
        public readonly string $path,
        public readonly string $extension,
    ) {}
}
