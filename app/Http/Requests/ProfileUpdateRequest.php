<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name" => ["required", "string", "max:255"],
            "email" => [
                "required",
                "string",
                "lowercase",
                "email",
                "max:255",
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            "phone" => ["nullable", "string", "max:20"],
            "bio" => ["nullable", "string", "max:500"],
            "avatar" => [
                "nullable",
                "image",
                "max:2048",
                "mimes:jpg,jpeg,png,webp",
            ],
        ];
    }

    public function messages(): array
    {
        return [
            "avatar.image" => "File harus berupa gambar.",
            "avatar.max" => "Ukuran foto maksimal 2MB.",
            "avatar.mimes" => "Format foto harus JPG, PNG, atau WebP.",
            "bio.max" => "Bio maksimal 500 karakter.",
            "phone.max" => "Nomor HP maksimal 20 karakter.",
        ];
    }
}
