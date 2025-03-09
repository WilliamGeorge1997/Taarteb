<?php


namespace Modules\Subject\DTO;

class SubjectDto
{
    public $name;
    public $grade_id;
    public function __construct($request)
    {
        if ($request->get('name'))
            $this->name = $request->get('name');
        if ($request->get('grade_id'))
            $this->grade_id = $request->get('grade_id');
    }


    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);
        if ($this->name == null)
            unset($data['name']);
        if ($this->grade_id == null)
            unset($data['grade_id']);
        return $data;
    }
}
