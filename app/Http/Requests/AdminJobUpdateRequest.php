<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminJobUpdateRequest extends FormRequest
{
    use Concerns\HasJobLocationRules;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return array_merge([
            'company_name' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'job_type' => ['nullable', Rule::in(['full_time', 'part_time', 'internship', 'contract'])],
            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'qualifications' => ['nullable', 'string'],
            'education' => ['nullable', 'string', 'max:255'],
            'experience' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'string', 'max:255'],
            'age_range' => ['nullable', 'string', 'max:255'],
            'work_hours' => ['nullable', 'string', 'max:255'],
            'benefits' => ['nullable', 'string'],
            'deadline' => ['nullable', 'date'],
            'status' => ['required', Rule::in(['active', 'closed', 'draft', 'pending', 'rejected', 'inactive'])],
        ], self::jobLocationRules());
    }

    public function withValidator($validator): void
    {
        $this->validateJobLocation($validator);
    }
}
