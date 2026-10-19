<?php

namespace App\Application\Contracts;

final class VideoFile
{
    public function __construct(
        public readonly string $path,
        public readonly string $extension,
    ) {}
}
