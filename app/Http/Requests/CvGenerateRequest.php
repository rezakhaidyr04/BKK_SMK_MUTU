<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CvGenerateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'include_photo' => 'boolean',
            'include_skills' => 'boolean',
            'include_certificates' => 'boolean',
            'custom_headline' => 'nullable|string|max:120',
            'custom_summary' => 'nullable|string|max:1200',
            'custom_experience' => 'nullable|string|max:2000',
            'custom_achievement' => 'nullable|string|max:500',
            'target_position' => 'nullable|string|max:120',
            'ats_keywords' => 'nullable|string|max:500',
        ];
    }
}
