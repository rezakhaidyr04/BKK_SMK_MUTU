<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * P0 C-01: Public view lowongan untuk API.
 * Company diserialisasi via CompanyPublicResource agar
 * tidak membocorkan data internal perusahaan.
 */
class JobResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'company_name' => $this->company_name,
            'title' => $this->title,
            'position' => $this->position,
            'location' => $this->location,
            'job_type' => $this->job_type,
            'salary_min' => $this->salary_min,
            'salary_max' => $this->salary_max,
            'description' => $this->description,
            'qualifications' => $this->qualifications,
            'benefits' => $this->benefits,
            'deadline' => $this->deadline,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'company' => new CompanyPublicResource($this->whenLoaded('company')),
        ];
    }
}
