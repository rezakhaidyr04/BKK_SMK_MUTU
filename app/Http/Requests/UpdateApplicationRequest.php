<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'string', 'in:submitted,under_review,interviewed,accepted,rejected'],
            'interview_date' => ['nullable', 'required_if:status,interviewed', 'date'],
            'interview_time' => ['nullable', 'required_if:status,interviewed', 'date_format:H:i'],
            'interview_type' => ['nullable', 'required_if:status,interviewed', 'in:online,offline'],
            'interview_link' => ['nullable', 'required_if:interview_type,online', 'url'],
            'interview_location' => ['nullable', 'required_if:interview_type,offline', 'string', 'max:255'],
            'interview_notes' => ['nullable', 'string'],
        ];
    }
}
