<?php

namespace App\Support;

use DateTimeInterface;
use Illuminate\Support\Carbon;

class Ui
{
    public static function dmy(mixed $value, string $placeholder = '—'): string
    {
        $dt = self::toCarbon($value);

        return $dt ? $dt->format('d/m/Y') : $placeholder;
    }

    public static function time(mixed $value, string $placeholder = '—'): string
    {
        $dt = self::toCarbon($value);

        return $dt ? $dt->format('H:i') : $placeholder;
    }

    private static function toCarbon(mixed $value): ?Carbon
    {
        if ($value instanceof Carbon) {
            return $value;
        }

        if ($value instanceof DateTimeInterface) {
            return Carbon::instance($value);
        }

        $string = trim((string) $value);
        if ($string === '' || $string === '0000-00-00' || $string === '0000-00-00 00:00:00') {
            return null;
        }

        try {
            return Carbon::parse($string);
        } catch (\Throwable) {
            return null;
        }
    }
}

