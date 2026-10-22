<?php

namespace App\Domain\Shared;

interface FileStorageInterface
{
    public function store(string $directory, UploadedFileData $file): string;

    public function delete(string $path): void;
}
