<?php


namespace Modules\Grade\DTO;

class GradeDto
{
    public $name;
    public $grade_category_id;
    public $school_id;
    public function __construct($request)
    {
        if ($request->get('name'))
            $this->name = $request->get('name');
        if ($request->get('grade_category_id'))
            $this->grade_category_id = $request->get('grade_category_id');
        if ($request->get('school_id'))
            $this->school_id = $request->get('school_id');
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);
        if ($this->name == null)
            unset($data['name']);
        if ($this->grade_category_id == null)
            unset($data['grade_category_id']);
        if ($this->school_id == null)
            unset($data['school_id']);
        return $data;
    }
}
