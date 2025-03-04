<?php

namespace Modules\Teacher\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->teacher->name,
            'email' => $this->teacher->email,
            'phone' => $this->teacher->phone,
            'image' => $this->teacher->image,
            'gender' => $this->gender,
            'grade' => $this->grade->name,
            'subject' => $this->subject->name,
            'school' => $this->teacher->school->name,
            'created_at' => $this->created_at->format('Y-m-d h:i A'),
            'updated_at' => $this->updated_at->format('Y-m-d h:i A'),
        ];
    }
}
