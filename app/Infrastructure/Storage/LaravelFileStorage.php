<?php

namespace App\Infrastructure\Storage;

use App\Application\Contracts\FileStorageInterface;
use App\Application\Contracts\VideoFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class LaravelFileStorage implements FileStorageInterface
{
    public function store(string $directory, VideoFile $file): string
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
