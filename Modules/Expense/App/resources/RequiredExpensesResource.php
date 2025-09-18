<?php

namespace Modules\Expense\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RequiredExpensesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'school_name' => $this->school->name,
            'grade_category_name' => $this->gradeCategory->name,
            'grade_name' => $this->grade->name,
            'price' => $this->price,
            'exceptions_price' => $this->exceptions->first()->pivot->exception_price ?? null,
        ];
    }
}
