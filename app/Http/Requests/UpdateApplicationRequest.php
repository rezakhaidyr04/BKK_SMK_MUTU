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
        // Form browser SELALU mengirim interview_type (default "offline") walau
        // status bukan interviewed — jadi syarat link/lokasi harus dibatasi
        // hanya saat status=interviewed, kalau tidak semua update status
        // (ditinjau/diterima/ditolak) gagal validasi secara diam-diam.
        return [
            'status' => ['required', 'string', 'in:submitted,under_review,interviewed,accepted,rejected'],
            'interview_date' => ['exclude_unless:status,interviewed', 'required', 'date'],
            'interview_time' => ['exclude_unless:status,interviewed', 'required', 'date_format:H:i'],
            'interview_type' => ['exclude_unless:status,interviewed', 'required', 'in:online,offline'],
            'interview_link' => ['exclude_unless:status,interviewed', 'exclude_unless:interview_type,online', 'required', 'url'],
            'interview_location' => ['exclude_unless:status,interviewed', 'exclude_unless:interview_type,offline', 'required', 'string', 'max:255'],
            'interview_notes' => ['nullable', 'string'],
        ];
    }
}
