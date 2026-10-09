<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobAlertRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'umum';
    }

    public function rules(): array
    {
        return [
            'keyword' => ['nullable', 'string', 'max:100'],
            'job_type' => ['nullable', 'string', 'max:50'],
            'province' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function withValidator($validator): void
    {
        // Tanpa kriteria = langganan semua lowongan = spam mingguan. Tolak.
        $validator->after(function ($validator) {
            $data = $this->only(['keyword', 'job_type', 'province', 'city', 'district']);
            if (! array_filter($data)) {
                $validator->errors()->add('keyword', 'Isi minimal satu kriteria (kata kunci, tipe, atau wilayah).');
            }
        });
    }

    public function messages(): array
    {
        return [
            'keyword.max' => 'Kata kunci maksimal 100 karakter.',
        ];
    }
}
