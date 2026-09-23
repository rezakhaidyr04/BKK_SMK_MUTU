<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminEventUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:job_fair,seminar,workshop,pelatihan,lainnya'],
            'description' => ['required', 'string'],
            'start_time' => ['required', 'date'],
            'end_time' => ['nullable', 'date', 'after:start_time'],
            'location' => ['required', 'string', 'max:255'],
            'poster' => ['nullable', 'image', 'max:3072', 'mimes:jpg,jpeg,png,webp'],
            'is_paid' => ['nullable', 'boolean'],
            'price' => ['nullable', 'required_if:is_paid,1', 'numeric', 'min:1000', 'max:10000000'],
            'quota' => ['nullable', 'integer', 'min:1', 'max:10000'],
            'payment_instructions' => ['nullable', 'string', 'max:1000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_paid' => $this->boolean('is_paid'),
        ]);
    }
}
