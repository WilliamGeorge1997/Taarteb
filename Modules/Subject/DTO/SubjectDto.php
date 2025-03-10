<?php


namespace Modules\Subject\DTO;

class SubjectDto
{
    public $name;
    public $grade_id;
    public $school_id;
    public function __construct($request)
    {
        if ($request->get('name'))
            $this->name = $request->get('name');
        if ($request->get('grade_id'))
            $this->grade_id = $request->get('grade_id');
            if (auth('user')->user()->hasRole('Super Admin')) {
                if ($request->get('school_id')) {
                    $this->school_id = $request->get('school_id');
                }
            } else if (auth('user')->user()->hasRole('School Manager')) {
                if ($request->isMethod('POST')) {
                    $this->school_id = auth('user')->user()->school_id;
                }
            }
    }


    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);
        if ($this->name == null)
            unset($data['name']);
        if ($this->grade_id == null)
            unset($data['grade_id']);
        if ($this->school_id == null)
            unset($data['school_id']);
        return $data;
    }
}

