<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\LearningClassResource;
use Illuminate\Http\Resources\Json\JsonResource;

class GradeResource extends JsonResource
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
            'student' => [
                'id' => optional($this->student)->student->user_id,
                'nim' => optional($this->student)->student->nim,
                'name' => optional($this->student)->student->name,
            ],
            'class' => new LearningClassResource($this->learningClass),
            'grade_type' => new GradeTypeResource($this->gradeType),
            'grade' => $this->grade
        ];
    }
}
