<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminNewsUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'content' => ['required', 'string'],
            'thumbnail' => ['nullable', 'image', 'max:2048', 'mimes:jpg,jpeg,png,webp'],
            'is_published' => ['nullable', 'boolean'],
        ];
    }
}
