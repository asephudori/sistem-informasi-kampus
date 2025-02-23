<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_data' => [
                'id' => optional($this->user)->id,
                'username' => optional($this->user)->username,
                'role' => optional($this->user)->role(),
            ],
            'advisory_class' => [
                'id' => optional($this->advisoryClass)->id,
                'lecturer_id' => optional($this->advisoryClass)->lecturer_id,
                'class_year' => optional($this->advisoryClass)->class_year,
            ],
            'study_program' => [
                'id' => optional($this->studyProgram)->id,
                'name' => optional($this->studyProgram)->name,
                'faculty' => [
                    'id' => optional(optional($this->studyProgram)->faculty)->id,
                    'name' => optional(optional($this->studyProgram)->faculty)->name,
                ],
            ],
            'nim' => $this->nim,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'birthplace' => $this->birthplace,
            'birthdate' => $this->birthdate,
            'gender' => $this->gender,
            'address' => [
                'home_address' => $this->home_address,
                'current_address' => $this->current_address,
                'home_city_district' => $this->home_city_district,
                'home_postal_code' => $this->home_postal_code,
            ]
        ];
    }
}
