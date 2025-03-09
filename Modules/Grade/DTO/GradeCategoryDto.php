<?php


namespace Modules\Grade\DTO;

class GradeCategoryDto
{
    public $name;
    public $school_id;

    public function __construct($request)
    {
        if ($request->get('name'))
            $this->name = $request->get('name');
        if ($request->get('school_id'))
            $this->school_id = $request->get('school_id');
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);
        if ($this->name == null)
            unset($data['name']);
        if ($this->school_id == null)
            unset($data['school_id']);
        return $data;
    }
}

