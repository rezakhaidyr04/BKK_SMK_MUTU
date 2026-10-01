<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tax_number' => ['nullable', 'string', 'max:100'],
            'business_license' => ['nullable', 'file', 'mimes:pdf', 'mimetypes:application/pdf', 'max:5120'],
            'operating_license' => ['nullable', 'file', 'mimes:pdf', 'mimetypes:application/pdf', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'business_license.mimes' => 'Surat izin usaha wajib berformat PDF.',
            'business_license.mimetypes' => 'Surat izin usaha wajib berformat PDF.',
            'operating_license.mimes' => 'Surat izin operasional wajib berformat PDF.',
            'operating_license.mimetypes' => 'Surat izin operasional wajib berformat PDF.',
        ];
    }
}
