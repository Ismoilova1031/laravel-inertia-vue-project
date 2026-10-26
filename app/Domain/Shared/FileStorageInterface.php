<?php

namespace App\Domain\Shared;

use Symfony\Component\HttpFoundation\StreamedResponse;

interface FileStorageInterface
{
    public function store(string $directory, UploadedFileData $file, bool $private = false): string;

    public function delete(string $path, bool $private = false): void;

    public function download(string $path, bool $private = false): StreamedResponse;

    public function response(string $path, bool $private = false): \Symfony\Component\HttpFoundation\StreamedResponse;
}
