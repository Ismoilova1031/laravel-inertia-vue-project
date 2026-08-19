<?php

namespace App\Repositories;

use App\Contracts\Repositories\StorageRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class StorageRepository implements StorageRepositoryInterface
{
    public function storeFile(string $path, UploadedFile $content): void
    {
        Storage::put($path, $content->getContent());
    }

    public function getFile(string $path): ?string
    {
        return Storage::get($path);
    }

    public function deleteFile(string $path): void
    {
        Storage::delete($path);
    }
}