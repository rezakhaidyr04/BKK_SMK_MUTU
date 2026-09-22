<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * P0 C-01: Public view perusahaan untuk API.
 * Hanya field publik yang boleh keluar. Field sensitif
 * (tax_number, *_path, mou_*, is_verified, verification_status,
 * rejection_reason, reviewed_by/at, email/phone/address privat)
 * TIDAK boleh ada di sini.
 */
class CompanyPublicResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'industry' => $this->industry,
            'logo' => $this->logo,
            'website' => $this->website,
            'description' => $this->description,
        ];
    }
}
