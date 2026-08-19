<?php

namespace App\Contracts\Repositories;

use Illuminate\Http\UploadedFile;

interface StorageRepositoryInterface
{
    public function storeFile(string $path, UploadedFile $content): void;

    public function getFile(string $path): ?string;

    public function deleteFile(string $path): void;
}