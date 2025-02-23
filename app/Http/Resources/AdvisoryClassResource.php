<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdvisoryClassResource extends JsonResource
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
                'id' => optional($this->lecturer)->id,
                'nidn' => optional(optional($this->lecturer)->lecturer)->nidn,
                'name' => optional(optional($this->lecturer)->lecturer)->name,
            ],
            'class_year' => $this->class_year,
        ], $request->query('members') === 'true' ? [
            'class-members' => $this->students->map(function ($student) {
                return [
                    'id' => optional($student)->user_id,
                    'nim' => optional($student)->nim,
                    'name' => optional($student)->name,
                ];
            })
        ] : []);
    }
}
