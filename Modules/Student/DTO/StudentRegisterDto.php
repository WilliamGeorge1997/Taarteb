<?php

namespace Modules\Student\DTO;
class StudentRegisterDto
{
    public $name;
    public $email;
    public $identity_number;
    public $parent_email;
    public $parent_phone;
    public $gender;
    public $grade_id;
    public $user_id;
    public $school_id;
    public $is_fee_paid;
    public $is_register;
    public $address;

    public function __construct($request , $user_id) {
        if($request->get('name')) $this->name = $request->get('name');
        if($request->get('email')) $this->email = $request->get('email');
        if($request->get('gender')) $this->gender = $request->get('gender');
        if($request->get('identity_number')) $this->identity_number = $request->get('identity_number');
        if($request->get('parent_email')) $this->parent_email = $request->get('parent_email');
        if($request->get('parent_phone')) $this->parent_phone = $request->get('parent_phone');
        if($request->get('grade_id')) $this->grade_id = $request->get('grade_id');
        if($request->get('school_id')) $this->school_id = $request->get('school_id');
        if($request->get('address')) $this->address = $request->get('address');
        $this->user_id = $user_id;
        $this->is_register = 1;
        $this->is_fee_paid = 0;
    }

    public function dataFromRequest()
    {
        $data =  json_decode(json_encode($this), true);
        if($this->name == null) unset($data['name']);
        if($this->email == null) unset($data['email']);
        if($this->identity_number == null) unset($data['identity_number']);
        if($this->parent_email == null) unset($data['parent_email']);
        if($this->parent_phone == null) unset($data['parent_phone']);
        if($this->gender == null) unset($data['gender']);
        if($this->grade_id == null) unset($data['grade_id']);
        if($this->school_id == null) unset($data['school_id']);
        if($this->address == null) unset($data['address']);
        return $data;
    }
}
