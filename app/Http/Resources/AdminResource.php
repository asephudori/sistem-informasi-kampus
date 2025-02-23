<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
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
            'permission_role' => [
                'id' => optional($this->permissionRole)->id,
                'name' => optional($this->permissionRole)->name,
                'description' => optional($this->permissionRole)->description,
            ],
            'name' => $this->name,
            'role' => $this->role,
        ];
    }
}
