<?php

namespace Modules\Common\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'day' => $this->day,
            'session_number' => $this->session_number,
            'semester' => $this->semester,
            'year' => $this->year,
            'student' => $this->student->name,
            'subject' => $this->subject->name,
            'class' => $this->class->name,
            'teacher' => $this->teacher->teacher->name,
            'attendance_taken_by' => $this->attendanceTakenBy->teacher->name,
            'school' => $this->school->name,
            'is_present' => $this->is_present,
            'created_at' => $this->created_at->format('Y-m-d h:i A'),
            'updated_at' => $this->updated_at->format('Y-m-d h:i A')
        ];
    }
}
