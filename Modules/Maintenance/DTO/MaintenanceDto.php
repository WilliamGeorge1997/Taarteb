<?php

namespace Modules\Maintenance\DTO;

class MaintenanceDto
{
    public $description;
    public $employee_id;
    public $school_id;
    public $date;
    public $price;
    public function __construct($request, $store = false)
    {
        if ($store) {
            $this->employee_id = auth('employee')->user()->id;
            $this->school_id = auth('employee')->user()->school_id;
        }
        if ($request->get('description'))
            $this->description = $request->get('description');
        if ($request->get('date'))
            $this->date = $request->get('date');
        if ($request->get('price'))
            $this->price = $request->get('price');
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);
        if ($this->employee_id == null)
            unset($data['employee_id']);
        if ($this->school_id == null)
            unset($data['school_id']);
        if ($this->description == null)
            unset($data['description']);
        if ($this->date == null)
            unset($data['date']);
        if ($this->price == null)
            unset($data['price']);
        return $data;
    }
}
