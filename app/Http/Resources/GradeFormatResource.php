<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GradeFormatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'min_grade' => $this->min_grade,
            'max_grade' => $this->max_grade,
            'alphabetical_grade' => $this->alphabetical_grade,
        ];
    }
}
