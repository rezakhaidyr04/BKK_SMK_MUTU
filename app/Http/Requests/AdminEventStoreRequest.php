<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminEventStoreRequest extends FormRequest
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
        ];
    }
}
