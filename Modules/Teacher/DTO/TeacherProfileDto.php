<?php


namespace Modules\Teacher\DTO;

use Illuminate\Support\Facades\Hash;

class TeacherProfileDto
{
    public $gender;
    public $image;
    public $subject_id;
    public $grade_id;

    public function __construct($request) {
        if($request->get('gender')) $this->gender = $request->get('gender');
        if($request->get('subject_id')) $this->subject_id = $request->get('subject_id');
        if($request->get('grade_id')) $this->grade_id = $request->get('grade_id');


    }

    public function dataFromRequest()
    {
        $data =  json_decode(json_encode($this), true);
        if($this->gender == null) unset($data['gender']);
        if($this->grade_id == null) unset($data['grade_id']);
        if($this->subject_id == null) unset($data['subject_id']);

        return $data;
    }
}
