<?php

namespace App\Application\Contracts;

interface FileStorageInterface
{
    public function store(string $directory, VideoFile $file): string;

    public function delete(string $path): void;
}