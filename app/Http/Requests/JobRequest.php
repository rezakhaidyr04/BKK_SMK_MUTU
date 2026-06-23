<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only companies can create or update jobs
        return $this->user()->hasRole('company');
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'job_type' => 'required|string|in:full_time,part_time,internship,contract',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|gte:salary_min',
            'description' => 'required|string',
            'qualifications' => 'required|string',
            'benefits' => 'nullable|string',
            'deadline' => 'nullable|date|after_or_equal:today',
            'status' => 'nullable|string|in:active,closed,draft',
        ];
    }
}
