<?php

namespace App\Infrastructure\Service;

use App\Domain\Shared\FileStorageInterface;
use App\Domain\Shared\UploadedFileData;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class LaravelFileStorage implements FileStorageInterface
{
    public function store(string $directory, UploadedFileData $file, bool $private = false): string
    {
        $path = trim($directory, '/') . '/' . Str::uuid() . '.' . $file->extension;
        $stream = fopen($file->path, 'rb');

        try {
            Storage::disk($private ? 'local' : 'public')->put($path, $stream);
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }

        return $path;
    }

    public function delete(string $path, bool $private = false): void
    {
        Storage::disk($private ? 'local' : 'public')->delete($path);
    }

    public function download(string $path, bool $private = false): StreamedResponse
    {
        return Storage::disk($private ? 'local' : 'public')->download($path);
    }

    public function response(string $path, bool $private = false): StreamedResponse
    {
        $response = Storage::disk($private ? 'local' : 'public')->response($path);
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        return $response;
    }
}
