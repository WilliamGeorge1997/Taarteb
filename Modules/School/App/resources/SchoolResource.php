<?php

namespace Modules\School\App\resources;

use Modules\User\App\resources\UserResource;
use Modules\Class\App\resources\ClassResource;
use Modules\Grade\App\resources\GradeResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Session\App\resources\SessionResource;
use Modules\Student\App\resources\StudentResource;
use Modules\Subject\App\resources\SubjectResource;

class SchoolResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_active' => (bool) $this->is_active,
            'manager' => [
                'id' => $this->whenLoaded('manager') ? $this->manager->id : null,
                'name' => $this->whenLoaded('manager') ? $this->manager->name : null,
                'email' => $this->whenLoaded('manager') ? $this->manager->email : null,
                'phone' => $this->whenLoaded('manager') ? $this->manager->phone : null,
                'image' => $this->whenLoaded('manager') ? $this->manager->image : null,
                'role' => $this->whenLoaded('manager') ? $this->manager->role : null,
                'created_at' => $this->whenLoaded('manager') ? $this->manager->created_at->format('Y-m-d h:i A') : null,
                'updated_at' => $this->whenLoaded('manager') ? $this->manager->updated_at->format('Y-m-d h:i A') : null,
            ],
            'grades' => GradeResource::collection($this->whenLoaded('grades')),
            'classes' => ClassResource::collection($this->whenLoaded('classes')),
            'subjects' => SubjectResource::collection($this->whenLoaded('subjects')),
            'teachers' => UserResource::collection($this->whenLoaded('teachers')),
            'students' => StudentResource::collection($this->whenLoaded('students')),
            'sessions' => SessionResource::collection($this->whenLoaded('sessions')),
            'created_at' => $this->created_at->format('Y-m-d h:i A'),
            'updated_at' => $this->updated_at->format('Y-m-d h:i A'),
        ];
    }
}
