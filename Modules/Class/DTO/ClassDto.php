<?php


namespace Modules\Class\DTO;


class ClassDto
{
    public $title;
    public $grade_id;
    public $max_students;
    public $period_number;

    public function __construct($request) {
        if($request->get('title')) $this->title = $request->get('title');
        if($request->get('grade_id')) $this->grade_id = $request->get('grade_id');
        if($request->get('max_students')) $this->max_students = $request->get('max_students');
        if($request->get('period_number')) $this->period_number = $request->get('period_number');
    }

    public function dataFromRequest()
    {
        $data =  json_decode(json_encode($this), true);
        if($this->title == null) unset($data['title']);
        if($this->grade_id == null) unset($data['grade_id']);
        if($this->max_students == null) unset($data['max_students']);
        if($this->period_number == null) unset($data['period_number']);
        return $data;
    }
}
