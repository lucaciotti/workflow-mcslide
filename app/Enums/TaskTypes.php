<?php

namespace App\Enums;

enum TaskTypes: string
{
    case ORD = 'ord';
    case SOST = 'sost';

    public function label(): string
    {
        return match ($this) {
            self::ORD => 'Ordine',
            self::SOST => 'Sostituzione'
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ORD => 'gray',
            self::SOST => 'red',
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
}
