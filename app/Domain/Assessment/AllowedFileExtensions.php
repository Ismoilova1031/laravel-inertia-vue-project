<?php

namespace App\Domain\Assessment;

use InvalidArgumentException;

final class AllowedFileExtensions
{
    private readonly array $items;

    public function __construct(array $items)
    {
        $unique = [];
        foreach($items as $item){
            $unique[$item->value] = $item;
        }

        if($unique === []){
            throw new InvalidArgumentException("Kamida bitta fayl kengaytmasi tanlanishi kerak.");
        }

        $this->items = array_values($unique);
    }

    public static function fromStrings(array $values): self
    {
        return new self(array_map(
            fn (string $v) => FileExtensions::tryFrom($v) 
                ?? throw new InvalidArgumentException("Ruxsat etilmagan kengaytma: {$v}"),
                $values,
        ));
    }

    public function values(): array 
    {
        return array_map(fn (FileExtensions $e) => $e->value, $this->items);
    }
}