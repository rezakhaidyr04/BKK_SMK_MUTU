<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'umum';
    }

    public function rules(): array
    {
        return [
            'reason' => ['required', 'in:penipuan,pungutan,info_palsu,diskriminasi,lainnya'],
            'detail' => ['nullable', 'string', 'min:10', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'reason.required' => 'Pilih alasan pelaporan.',
            'detail.min' => 'Detail minimal 10 karakter agar bisa ditindaklanjuti.',
        ];
    }
}
