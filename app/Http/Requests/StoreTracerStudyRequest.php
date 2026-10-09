<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTracerStudyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'umum';
    }

    public function rules(): array
    {
        $year = (int) now()->format('Y');

        return [
            'status_kerja' => ['required', 'in:bekerja,kuliah,wirausaha,menganggur'],
            // Wajib saat sudah ada kegiatan, opsional saat menganggur.
            'company_name' => ['nullable', 'string', 'max:150', 'required_unless:status_kerja,menganggur'],
            'position' => ['nullable', 'string', 'max:100'],
            'salary_range' => ['nullable', 'in:<3jt,3-5jt,5-10jt,>10jt,rahasia'],
            'is_relevant' => ['nullable', 'boolean'],
            'tahun_lulus' => ['nullable', 'integer', 'min:2000', "max:{$year}"],
            'jurusan' => ['nullable', 'string', 'max:100'],
            'no_wa' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'status_kerja.required' => 'Pilih status Anda saat ini.',
            'company_name.required_unless' => 'Nama perusahaan/kampus/usaha wajib diisi.',
            'no_wa.regex' => 'Nomor WA hanya boleh berisi angka, +, - dan spasi.',
        ];
    }
}
