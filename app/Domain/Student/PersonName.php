<?php

namespace App\Domain\Student;

use InvalidArgumentException;

final class PersonName
{
    private readonly string $name;
    private readonly string $surname;

    public function __construct(string $name, string $surname)
    {
        $name = trim($name);
        $surname = trim($surname);

        foreach([$name, $surname] as $part){
            if($part === '' || mb_strlen($part) > 255){
                throw new InvalidArgumentException('Ism va familiya bo\'sh bo\'lmasligi va 255 belgidan oshmasligi kerak');
            }
        }

        $this->name = $name;
        $this->surname = $surname;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function surname(): string
    {
        return $this->surname;
    }

    public function fullName(): string
    {
        return $this->name . ' ' . $this->surname;
    }

    public function equals(self $other): bool
    {
        return $this->name === $other->name && $this->surname === $other->surname;
    }
}