<?php

namespace Modules\Admin\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "image" => $this->image,
            "school_id" => $this->school_id,
            "remember_token" => $this->remember_token,
            "is_active" => $this->is_active,
            "created_at" => $this->created_at->format('Y-m-d h:i A'),
            "updated_at" => $this->updated_at->format('Y-m-d h:i A'),
            'role' => $this->roles->first()->name,
            'permissions' => $this->roles->first()->permissions->groupBy('category')->map(function ($permissions) {
                return $permissions->pluck('name')->toArray();
            }),
        ];
    }
}
