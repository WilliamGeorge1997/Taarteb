<?php


namespace Modules\School\DTO;


class SchoolDto
{
    public $name;
    public $email;
    public $phone;
    public $grade;
    public $manager_name;
    public $manager_email;
    public $manager_password;

    public function __construct($request) {
        $this->name = $request->get('name');
        $this->email = $request->get('email');
        $this->phone = $request->get('phone');
        $this->grade = $request->get('grade');
        $this->manager_name = $request->get('manager_name');
        $this->manager_email = $request->get('manager_email');
        $this->manager_password = $request->get('manager_password');
    }

    public function dataFromRequest()
    {
        $data =  json_decode(json_encode($this), true);
        if($this->name == null) unset($data['name']);
        if($this->email == null) unset($data['email']);
        if($this->phone == null) unset($data['phone']);
        if($this->grade == null) unset($data['grade']);
        if($this->manager_name == null) unset($data['manager_name']);
        if($this->manager_email == null) unset($data['manager_email']);
        if($this->manager_password == null) unset($data['manager_password']);
        return $data;
    }
}
