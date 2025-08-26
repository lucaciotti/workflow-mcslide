<?php

namespace App\Enums;

enum AttributeTypes: string
{
    case NUM = 'num';
    case STRING = 'string';
    case BOOL = 'bool';
    case DATE = 'date';
    case NOTE = 'note';

    public function label(): string
    {
        return match ($this) {
            self::NUM => 'Numerico',
            self::STRING => 'Alfanumerico',
            self::BOOL => 'Logico',
            self::DATE => 'Data',
            self::NOTE => 'Note',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::NUM => 'gray',
            self::STRING => 'blue',
            self::BOOL => 'green',
            self::DATE => 'green',
            self::NOTE => 'green',
        };
    }

    public static function labels(): array
    {
        return array_map(fn($category) => $category->label(), self::cases());
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function array(): array
    {
        return array_combine(self::values(), self::labels());
    }

    // public function isFinalized(): bool
    // {
    //     return in_array($this, [self::COMPLETED, self::CANCELLED, self::REFUNDED]);
    // }
}
