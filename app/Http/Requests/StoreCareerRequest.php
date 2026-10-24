<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCareerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Subset ProfileUpdateRequest khusus data karier (sumber & aturan IDENTIK,
     * hanya tanpa field akun agar form CV tidak perlu mengirimnya).
     */
    public function rules(): array
    {
        return [
            "preferred_position" => ["nullable", "string", "max:100"],
            "bio" => ["nullable", "string", "max:500"],
            "education_history" => ["nullable", "string"],
            "experience_organization" => ["nullable", "string"],
            "linkedin_url" => ["nullable", "url", "max:255"],
            "portfolio_url" => ["nullable", "url", "max:255"],
            "portfolio_type" => ["nullable", "string", "in:website,drive"],
            "skills" => ["nullable", "array", "max:20"],
            // Izinkan karakter umum pada nama skill: "UI/UX", "HTML & CSS", "Ms. Office (Excel)", dll.
            "skills.*" => ["string", "max:50", "regex:/^[\\p{L}\\p{N} .+#\\-\\/\\&\\(\\)\\',]+$/u"],
        ];
    }

    public function messages(): array
    {
        return [
            "bio.max" => "Bio maksimal 500 karakter.",
            "linkedin_url.url" => "Format LinkedIn URL tidak valid.",
            "portfolio_url.url" => "Format URL portofolio tidak valid.",
            "skills.max" => "Maksimal 20 keahlian.",
            "skills.*.max" => "Setiap keahlian maksimal 50 karakter.",
            "skills.*.regex" => "Keahlian hanya boleh berisi huruf, angka, spasi, dan tanda umum (./&#-+ dll).",
        ];
    }
}
