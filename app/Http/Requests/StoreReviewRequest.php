<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000|min:10',
            'job_title' => 'nullable|string|max:100',
            // M5/L5: ulasan wajib menunjuk perusahaan terdaftar (asosiasi
            // tidak boleh NULL) agar company_id selalu terisi dan ulasan
            // tidak tercampur antar perusahaan bernama sama.
            'company_name' => 'required|string|max:150|exists:companies,name',
            'name' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|string|max:20',
        ];
    }
}
