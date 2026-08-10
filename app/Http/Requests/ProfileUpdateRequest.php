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
            "linkedin_url" => ["nullable", "url", "max:255"],
            "portfolio_url" => ["nullable", "url", "max:255"],
            "preferred_position" => ["nullable", "string", "max:100"],
            "education_history" => ["nullable", "string"],
            "experience_organization" => ["nullable", "string"],
            "birth_place" => ["nullable", "string", "max:100"],
            "birth_date" => ["nullable", "date"],
            "gender" => ["nullable", "string", "max:20"],
            "avatar" => [
                "nullable",
                "image",
                "max:3072",
                "mimes:jpg,jpeg,png,webp,gif",
            ],
        ];
    }

    public function messages(): array
    {
        return [
            "avatar.image" => "File harus berupa gambar.",
            "avatar.max" => "Ukuran foto maksimal 3MB.",
            "avatar.mimes" => "Format foto harus JPG, PNG, atau WebP.",
            "bio.max" => "Bio maksimal 500 karakter.",
            "phone.max" => "Nomor HP maksimal 20 karakter.",
            "linkedin_url.url" => "Format LinkedIn URL tidak valid.",
            "portfolio_url.url" => "Format URL portofolio tidak valid.",
        ];
    }
}
