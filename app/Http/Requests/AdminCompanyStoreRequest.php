<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminCompanyStoreRequest extends FormRequest
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
            'tax_number' => ['nullable', 'string', 'max:100'],
            'mou_path' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'mou_number' => ['nullable', 'string', 'max:255'],
            'mou_signed_at' => ['nullable', 'date'],
            'mou_expires_at' => ['nullable', 'date', 'after_or_equal:mou_signed_at'],
        ];
    }
}
