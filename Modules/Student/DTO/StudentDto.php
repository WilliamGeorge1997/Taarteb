<?php


namespace Modules\Student\DTO;


class StudentDto
{
    public $name;
    public $email;
    public $identity_number;
    public $parent_email;
    public $parent_phone;
    public $gender;
    public $grade_id;
    public $class_id;
    public $school_id;
    public $is_fee_paid;

    public function __construct($request) {
        if($request->get('name')) $this->name = $request->get('name');
        if($request->get('email')) $this->email = $request->get('email');
        if($request->get('gender')) $this->gender = $request->get('gender');
        if($request->get('identity_number')) $this->identity_number = $request->get('identity_number');
        if($request->get('parent_email')) $this->parent_email = $request->get('parent_email');
        if($request->get('parent_phone')) $this->parent_phone = $request->get('parent_phone');
        if($request->get('grade_id')) $this->grade_id = $request->get('grade_id');
        if($request->get('class_id')) $this->class_id = $request->get('class_id');
        if (auth('user')->user()->hasRole('Super Admin')) {
            if ($request->get('school_id')) {
                $this->school_id = $request->get('school_id');
            }
        } else if (auth('user')->user()->hasRole('School Manager')) {
            if ($request->isMethod('POST')) {
                $this->school_id = auth('user')->user()->school_id;
            }
        }
        if($request->get('is_fee_paid')) $this->is_fee_paid = $request->get('is_fee_paid');
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
        if($this->class_id == null) unset($data['class_id']);
        if($this->school_id == null) unset($data['school_id']);
        if($this->is_fee_paid == null) unset($data['is_fee_paid']);
        return $data;
    }
}
