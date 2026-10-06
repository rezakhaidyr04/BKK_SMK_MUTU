<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyJobStoreRequest extends FormRequest
{
    use Concerns\HasJobLocationRules;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return array_merge([
            'title' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'job_type' => ['nullable', 'string'],
            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'min:0'],
            'description' => ['required', 'string'],
            'qualifications' => ['required', 'string'],
            'education' => ['nullable', 'string', 'max:255'],
            'experience' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'string', 'max:255'],
            'age_range' => ['nullable', 'string', 'max:255'],
            'work_hours' => ['nullable', 'string', 'max:255'],
            'benefits' => ['nullable', 'string'],
            'deadline' => ['nullable', 'date', 'after_or_equal:today'],
        ], self::jobLocationRules());
    }

    public function withValidator($validator): void
    {
        $this->validateJobLocation($validator);
    }
}
