<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Students and Alumni can apply
        return in_array($this->user()?->role, ['student', 'alumni'], true);
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
