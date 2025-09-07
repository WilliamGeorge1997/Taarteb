<?php

namespace Modules\Salary\DTO;

class SalaryDto
{
    public $created_by;
    public $employee_id;
    public $school_id;
    public $salary;
    public $month;
    public $year;
    public function __construct($request, $store = false)
    {
        if ($store) {
            $this->created_by = auth('employee')->id();
            $this->school_id = auth('employee')->user()->school_id;
        }
        if ($request->get('employee_id'))
            $this->employee_id = $request->get('employee_id');
        if ($request->get('salary'))
            $this->salary = $request->get('salary');
        if ($request->get('month'))
            $this->month = $request->get('month');
        if ($request->get('year'))
            $this->year = $request->get('year');
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);
        if($data['created_by'] == null) unset($data['created_by']);
        if($data['school_id'] == null) unset($data['school_id']);
        return $data;
    }
}
