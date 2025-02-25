<?php


namespace Modules\School\DTO;

class SchoolDto
{
    public $name;
    public $email;
    public $phone;
    public $grade_id;

    public function __construct($request) {
        if($request->get('name')) $this->name = $request->get('name');
        if($request->get('email')) $this->email = $request->get('email');
        if($request->get('phone')) $this->phone = $request->get('phone');
        if($request->get('grade_id')) $this->grade_id = $request->get('grade_id');
    }

    public function dataFromRequest()
    {
        $data =  json_decode(json_encode($this), true);
        if($this->name == null) unset($data['name']);
        if($this->email == null) unset($data['email']);
        if($this->phone == null) unset($data['phone']);
        if($this->grade_id == null) unset($data['grade_id']);
        return $data;
    }
}
