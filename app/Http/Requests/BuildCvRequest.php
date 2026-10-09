<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuildCvRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Satu form = data karier (disimpan) + opsi generate (dispatch PDF).
     * Aturan diambil dari masing-masing Request agar tidak drift.
     */
    public function rules(): array
    {
        return array_merge(
            (new StoreCareerRequest)->rules(),
            (new CvGenerateRequest)->rules()
        );
    }

    /**
     * Pisahkan hasil validasi untuk CvBuilderService::generateCv().
     */
    public function generateData(): array
    {
        return $this->only([
            'include_photo', 'include_skills', 'include_certificates',
            'custom_headline', 'custom_summary', 'custom_experience',
            'custom_achievement', 'target_position', 'ats_keywords',
        ]);
    }
}
