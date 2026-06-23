<?php

namespace App\Support;

class Label
{
    public static function applicationStatus(?string $status): string
    {
        return self::translate('application_status', $status);
    }

    public static function jobStatus(?string $status): string
    {
        return self::translate('job_status', $status);
    }

    public static function jobType(?string $type): string
    {
        return self::translate('job_type', $type);
    }

    public static function role(?string $role): string
    {
        return self::translate('role', $role);
    }

    public static function eventType(?string $type): string
    {
        return self::translate('event_type', $type);
    }

    public static function newsCategory(?string $category): string
    {
        return self::translate('news_category', $category);
    }

    private static function translate(string $group, ?string $key): string
    {
        if ($key === null || $key === '') {
            return '-';
        }

        $translation = __("bkk.{$group}.{$key}");

        if ($translation !== "bkk.{$group}.{$key}") {
            return $translation;
        }

        return ucfirst(str_replace('_', ' ', $key));
    }
}
