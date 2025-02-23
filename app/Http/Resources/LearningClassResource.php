<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LearningClassResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge([
            'id' => $this->id,
            'lecturer' => [
                'id' => optional($this->lecturer)->lecturer->user_id,
                'nidn' => optional($this->lecturer)->lecturer->nidn,
                'name' => optional($this->lecturer)->lecturer->name,
            ],
            'course' => [
                'id' => optional($this->course)->id,
                'name' => optional($this->course)->name,
            ],
            'semester' => [
                'id' => optional($this->semester)->id,
                'name' => optional($this->semester)->name,
                'start_periode' => optional($this->semester)->start_periode,
                'end_periode' => optional($this->semester)->end_periode,
            ],
        ], $request->query('members') === 'true' ? [
            'class-members' => $this->students->map(function ($student) {
                return [
                    'id' => optional($student)->user_id,
                    'nim' => optional($student)->nim,
                    'name' => optional($student)->name,
                    'email' => optional($student)->email,
                ];
            })
        ] : []);
    }
}
