<?php

namespace App\Enums;

enum FileExtensions: string
{
    case PDF = 'pdf';
    case DOC = 'doc';
    case DOCX = 'docx';
    case XLS = 'xls';
    case XLSX = 'xlsx';
    case PPTX = 'pptx';
    case TXT = 'txt';
    case JPG = 'jpg';
    case PNG = 'png';
    case GIF = 'gif';

    public function label(): string
    {
        return match ($this) {
            self::PDF => 'PDF',
            self::DOC => 'DOC',
            self::DOCX => 'DOCX',
            self::XLS => 'XLS',
            self::XLSX => 'XLSX',
            self::PPTX => 'PPTX',
            self::TXT => 'TXT',
            self::JPG => 'JPG',
            self::PNG => 'PNG',
            self::GIF => 'GIF',
        };
    }

    public static function labels(): array
    {
        return collect(self::cases())->map(fn($case) => ['label' => $case->label(), 'value' => $case->value])->toArray();
    }
}