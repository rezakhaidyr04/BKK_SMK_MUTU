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
            'is_alumni' => ['required', 'boolean'],
            'status_kerja' => ['required', 'in:bekerja,kuliah,wirausaha,menganggur'],
            // Wajib saat sudah ada kegiatan, opsional saat menganggur.
            'company_name' => ['nullable', 'string', 'max:150', 'required_unless:status_kerja,menganggur'],
            'position' => ['nullable', 'string', 'max:100'],
            'salary_range' => ['nullable', 'in:<3jt,3-5jt,5-10jt,>10jt,rahasia'],
            'is_relevant' => ['nullable', 'boolean'],
            'tahun_lulus' => ['nullable', 'integer', 'min:2000', "max:{$year}"],
            'jurusan' => ['nullable', 'string', 'max:100'],
            'asal_sekolah' => ['nullable', 'string', 'max:150'],
            'no_wa' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
        ];
    }

    public function withValidator($validator): void
    {
        // Alumni wajib isi tahun lulus + jurusan (KPI sekolah);
        // non-alumni wajib isi asal sekolah sebagai gantinya.
        $validator->after(function ($validator) {
            if ($this->boolean('is_alumni')) {
                if (! $this->filled('tahun_lulus')) {
                    $validator->errors()->add('tahun_lulus', 'Tahun lulus wajib diisi untuk alumni.');
                }
                if (! $this->filled('jurusan')) {
                    $validator->errors()->add('jurusan', 'Jurusan wajib diisi untuk alumni.');
                }
            } elseif (! $this->filled('asal_sekolah')) {
                $validator->errors()->add('asal_sekolah', 'Asal sekolah wajib diisi bila bukan alumni SMK TI Muhammadiyah Cikampek.');
            }
        });
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
