<?php

namespace Modules\Employee\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        $data = parent::toArray($request);
        $data['role'] = $this->roles->first()->name ?? null;
        unset($data['roles']);
        return $data;
    }
}
