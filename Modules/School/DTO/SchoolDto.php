<?php


namespace Modules\School\DTO;

class SchoolDto
{
    public $name;
    public $email;
    public $phone;
    public $grade;

    public function __construct($request) {
        $this->name = $request->get('name');
        $this->email = $request->get('email');
        $this->phone = $request->get('phone');
        $this->grade = $request->get('grade');
    }

    public function dataFromRequest()
    {
        $data =  json_decode(json_encode($this), true);
        if($this->name == null) unset($data['name']);
        if($this->email == null) unset($data['email']);
        if($this->phone == null) unset($data['phone']);
        if($this->grade == null) unset($data['grade']);
        return $data;
    }
}
