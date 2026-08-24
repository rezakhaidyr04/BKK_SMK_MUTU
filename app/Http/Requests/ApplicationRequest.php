<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Jobseekers can apply
        return $this->user()?->role === 'jobseeker';
    }

    public function rules(): array
    {
        return [
            'cover_letter' => ['required', 'string', 'min:100', 'max:2000'],
            'attachment' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:5120',
            ],
        ];
    }
}
