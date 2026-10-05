<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminCompanyUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'website' => ['nullable', 'url', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'maps_url' => ['required', 'url', 'max:500'],
            'tax_number' => ['nullable', 'string', 'max:100'],
            'mou_path' => ['nullable', 'file', 'mimes:pdf', 'mimetypes:application/pdf', 'max:10240'],
            'mou_number' => ['nullable', 'string', 'max:255'],
            'mou_signed_at' => ['nullable', 'date'],
            'mou_expires_at' => ['nullable', 'date', 'after_or_equal:mou_signed_at'],
            'is_verified' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'mou_path.mimes' => 'File MoU wajib berformat PDF.',
            'mou_path.mimetypes' => 'File MoU wajib berformat PDF.',
        ];
    }
}
