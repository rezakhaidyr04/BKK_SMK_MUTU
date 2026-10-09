<?php

namespace App\Support;

class Mask
{
    /**
     * Samarkan nomor HP: 2 depan + x + 2 belakang ("08123456789" → "08xxxxxxx89").
     * Nomor pendek (≤4 digit) disamarkan penuh.
     */
    public static function phone(?string $phone): string
    {
        if ($phone === null || trim($phone) === '') {
            return '-';
        }

        $digits = preg_replace('/\D/', '', $phone) ?? '';
        $len = strlen($digits);

        if ($len <= 4) {
            return str_repeat('x', max($len, 1));
        }

        return substr($digits, 0, 2).str_repeat('x', $len - 4).substr($digits, -2);
    }

    /**
     * Tautan wa.me internasional (0 → 62). Null bila nomor kosong/tak valid.
     */
    public static function waLink(?string $phone): ?string
    {
        $digits = preg_replace('/\D/', '', (string) $phone) ?? '';

        if (strlen($digits) < 9) {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        }

        return 'https://wa.me/'.$digits;
    }
}
