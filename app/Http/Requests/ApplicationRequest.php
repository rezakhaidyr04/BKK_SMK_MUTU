<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Jobseekers can apply
        return $this->user()?->role === 'umum';
    }

    public function rules(): array
    {
        return [
            // Surat lamaran sekarang wajib upload PDF (ganti ketik manual).
            // cover_letter teks lama tetap nullable untuk backward-compat data lama.
            'cover_letter' => ['nullable', 'string', 'max:2000'],
            'cover_letter_file' => [
                'required',
                'file',
                'mimes:pdf',
                'mimetypes:application/pdf',
                'max:5120',
            ],
            'attachment' => [
                'nullable',
                'file',
                'mimes:pdf',
                'mimetypes:application/pdf',
                'max:5120',
            ],
            'skck_file' => [
                'nullable',
                'file',
                'mimes:pdf',
                'mimetypes:application/pdf',
                'max:5120',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'cover_letter_file.required' => 'Surat lamaran wajib diunggah (PDF).',
            'cover_letter_file.mimes' => 'Surat lamaran wajib berformat PDF.',
            'cover_letter_file.mimetypes' => 'Surat lamaran wajib berformat PDF.',
            'attachment.mimes' => 'File CV wajib berformat PDF.',
            'attachment.mimetypes' => 'File CV wajib berformat PDF.',
            'skck_file.mimes' => 'SKCK wajib berformat PDF.',
            'skck_file.mimetypes' => 'SKCK wajib berformat PDF.',
        ];
    }
}
