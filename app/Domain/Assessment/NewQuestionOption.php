<?php

namespace App\Domain\Assessment;

use InvalidArgumentException;

final class NewQuestionOption
{
    private readonly string $text;

    public function __construct(string $text, private readonly bool $isCorrect)
    {
        $text = trim($text);

        if ($text === '' || mb_strlen($text) > 255) {
            throw new InvalidArgumentException('Variant matni bo\'sh bo\'lmasligi va 255 belgidan oshmasligi kerak.');
        }

        $this->text = $text;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function isCorrect(): bool
    {
        return $this->isCorrect;
    }
}
