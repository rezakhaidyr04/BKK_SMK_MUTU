<?php

namespace App\Http\Requests\Concerns;

use App\Support\IndonesiaRegions;
use Illuminate\Validation\Rule;

/**
 * Aturan lokasi nasional untuk form lowongan (company + admin).
 * province/city tetap OPSIONAL (backward-compatible: request lama yang
 * hanya mengirim 'location' tetap lolos). Bila salah satu diisi, keduanya
 * wajib dan city harus milik province tersebut (anti manipulasi request).
 */
trait HasJobLocationRules
{
    public static function jobLocationRules(): array
    {
        return [
            'province' => ['nullable', 'string', 'max:100', Rule::in(IndonesiaRegions::provinces())],
            'city' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function validateJobLocation($validator): void
    {
        $validator->after(function ($validator) {
            $data = $validator->getData();
            $province = $data['province'] ?? null;
            $city = $data['city'] ?? null;

            if ($province && ! $city) {
                $validator->errors()->add('city', 'Pilih kabupaten/kota untuk provinsi tersebut.');
            }

            if ($city && ! $province) {
                $validator->errors()->add('province', 'Pilih provinsi untuk kabupaten/kota tersebut.');
            }

            if ($province && $city && ! IndonesiaRegions::isValidCity($province, $city)) {
                $validator->errors()->add('city', 'Kabupaten/kota tidak termasuk dalam provinsi tersebut.');
            }

            $district = $data['district'] ?? null;

            if ($district && (! $province || ! $city)) {
                $validator->errors()->add('district', 'Pilih provinsi dan kabupaten/kota untuk kecamatan tersebut.');
            }

            if ($province && $city && $district && ! IndonesiaRegions::isValidDistrict($province, $city, $district)) {
                $validator->errors()->add('district', 'Kecamatan tidak termasuk dalam kabupaten/kota tersebut.');
            }
        });
    }
}
