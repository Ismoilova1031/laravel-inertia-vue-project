<?php

namespace App\Infrastructure\Service;

use App\Domain\Shared\FileStorageInterface;
use App\Domain\Shared\UploadedFileData;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class LaravelFileStorage implements FileStorageInterface
{
    public function store(string $directory, UploadedFileData $file): string
    {
        $path = trim($directory, '/').'/'.Str::uuid().'.'.$file->extension;

        $stream = fopen($file->path, 'rb');

        try {
            Storage::disk('public')->put($path, $stream);
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }

        return $path;
    }

    public function delete(string $path): void
    {
        Storage::disk('public')->delete($path);
    }
}
