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
            'cover_letter' => ['required', 'string', 'min:100', 'max:2000'],
            'attachment' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/zip,image/jpeg,image/png',
                'max:5120',
            ],
        ];
    }
}
