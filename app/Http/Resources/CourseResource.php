<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'name' => $this->name,
            'prerequisite-course' => [
                'id' => optional($this->prerequisite)->id,
                'name' => optional($this->prerequisite)->name,
            ],
            'dependet-courses' => [
                $this->dependentCourses->map(function ($dependentCourse) {
                    return [
                        'id' => optional($dependentCourse)->id,
                        'name' => optional($dependentCourse)->name,
                    ];
                })
            ]
        ];
    }
}
