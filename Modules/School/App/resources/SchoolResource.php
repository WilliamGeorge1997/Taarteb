<?php

namespace Modules\School\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SchoolResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->manager->email,
            'phone' => $this->manager->phone,
            'created_at' => $this->created_at->format('Y-m-d h:i A'),
            'updated_at' => $this->updated_at->format('Y-m-d h:i A'),
        ];
    }
}
