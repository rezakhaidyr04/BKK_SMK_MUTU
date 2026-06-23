<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Students and Alumni can apply
        return $this->user()->hasAnyRole(['student', 'alumni']);
    }

    public function rules(): array
    {
        return [
            'job_id' => 'required|exists:jobs,id',
            'cover_letter' => 'nullable|string|max:2000',
        ];
    }
}
