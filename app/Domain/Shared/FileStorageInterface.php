<?php

namespace App\Domain\Shared;

interface FileStorageInterface
{
    public function store(string $directory, UploadedFileData $file, bool $private = false): string;
    
    public function delete(string $path, bool $private = false): void;
}
