<?php

namespace App\Support;

/**
 * Master wilayah Indonesia untuk lokasi lowongan (provinsi → kab/kota).
 * Data: config/regions.php (ibnux/data-indonesia, cermin Kemendagri).
 * Semua pencocokan case-insensitive agar input "jawa barat" tetap valid.
 */
class IndonesiaRegions
{
    /**
     * @return string[]
     */
    public static function provinces(): array
    {
        return config('regions.provinces', []);
    }

    /**
     * @return string[]
     */
    public static function citiesFor(?string $province): array
    {
        if (! $province) {
            return [];
        }

        foreach (config('regions.cities', []) as $name => $cities) {
            if (mb_strtolower($name) === mb_strtolower(trim($province))) {
                return $cities;
            }
        }

        return [];
    }

    public static function isValidProvince(?string $province): bool
    {
        if (! $province) {
            return false;
        }

        foreach (self::provinces() as $name) {
            if (mb_strtolower($name) === mb_strtolower(trim($province))) {
                return true;
            }
        }

        return false;
    }

    public static function canonicalProvince(?string $province): ?string
    {
        if (! $province) {
            return null;
        }

        foreach (self::provinces() as $name) {
            if (mb_strtolower($name) === mb_strtolower(trim($province))) {
                return $name;
            }
        }

        return null;
    }

    public static function isValidCity(?string $province, ?string $city): bool
    {
        if (! $province || ! $city) {
            return false;
        }

        foreach (self::citiesFor($province) as $name) {
            if (mb_strtolower($name) === mb_strtolower(trim($city))) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string[]
     */
    public static function districtsFor(?string $province, ?string $city): array
    {
        if (! $province || ! $city) {
            return [];
        }

        foreach (config('regions.districts', []) as $provName => $cities) {
            if (mb_strtolower($provName) !== mb_strtolower(trim($province))) {
                continue;
            }
            foreach ($cities as $cityName => $districts) {
                if (mb_strtolower($cityName) === mb_strtolower(trim($city))) {
                    return $districts;
                }
            }
        }

        return [];
    }

    public static function isValidDistrict(?string $province, ?string $city, ?string $district): bool
    {
        if (! $province || ! $city || ! $district) {
            return false;
        }

        foreach (self::districtsFor($province, $city) as $name) {
            if (mb_strtolower($name) === mb_strtolower(trim($district))) {
                return true;
            }
        }

        return false;
    }

    public static function canonicalDistrict(?string $province, ?string $city, ?string $district): ?string
    {
        if (! $province || ! $city || ! $district) {
            return null;
        }

        foreach (self::districtsFor($province, $city) as $name) {
            if (mb_strtolower($name) === mb_strtolower(trim($district))) {
                return $name;
            }
        }

        return null;
    }

    /**
     * Nama pendek untuk fallback LIKE baris legacy
     * ("Kabupaten Karawang" → "Karawang").
     */
    public static function shortName(string $city): string
    {
        return trim((string) preg_replace('/^(kabupaten|kota)\s+/i', '', trim($city)));
    }

    /**
     * Samakan input lama ("Karawang") ke pasangan kanonis bila UNIK.
     * Ambigu ("Bandung" → kab+kota, "Jakarta" → 6 kota) → null agar aman.
     *
     * @return array{province: ?string, city: ?string}
     */
    public static function matchLegacyLocation(?string $location): array
    {
        $location = trim((string) $location);

        if ($location === '') {
            return ['province' => null, 'city' => null];
        }

        $hits = [];

        foreach (config('regions.cities', []) as $province => $cities) {
            foreach ($cities as $city) {
                if (mb_strtolower($city) === mb_strtolower($location)
                    || mb_strtolower(self::shortName($city)) === mb_strtolower($location)) {
                    $hits[] = ['province' => $province, 'city' => $city];
                }
            }
        }

        return count($hits) === 1 ? $hits[0] : ['province' => null, 'city' => null];
    }

    /**
     * Normalisasi hasil validasi form job menjadi kolom DB.
     * province+city valid → location "Kota X, Provinsi Y" (display konsisten).
     * Hanya location → pertahankan + isi province/city bila cocok unik.
     *
     * @return array{province: ?string, city: ?string, district: ?string, location: ?string}
     */
    public static function normalizeJobLocation(array $validated): array
    {
        $province = self::canonicalProvince($validated['province'] ?? null);
        $city = trim((string) ($validated['city'] ?? ''));
        $city = $city === '' ? null : $city;
        $location = trim((string) ($validated['location'] ?? ''));
        $location = $location === '' ? null : $location;
        $district = trim((string) ($validated['district'] ?? ''));
        $district = $district === '' ? null : $district;

        if ($province && $city) {
            // Samakan ejaan city ke kanonis bila cocok longgar.
            foreach (self::citiesFor($province) as $name) {
                if (mb_strtolower($name) === mb_strtolower($city)
                    || mb_strtolower(self::shortName($name)) === mb_strtolower($city)) {
                    $city = $name;
                    break;
                }
            }

            $district = $district ? self::canonicalDistrict($province, $city, $district) : null;

            return [
                'province' => $province,
                'city' => $city,
                'district' => $district,
                'location' => $city.', '.$province,
            ];
        }

        if ($location && ! $province && ! $city) {
            $match = self::matchLegacyLocation($location);

            return [
                'province' => $match['province'],
                'city' => $match['city'],
                'district' => null,
                'location' => $location,
            ];
        }

        return ['province' => $province, 'city' => $city, 'district' => $district ? self::canonicalDistrict($province, $city, $district) : null, 'location' => $location];
    }
}
