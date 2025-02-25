<?php


namespace Modules\Teacher\DTO;

use Illuminate\Support\Facades\Hash;

class TeacherDto
{
    public $name;
    public $email;
    public $phone;
    public $password;
    public $image;
    public $subject_id;
    public $grade_id;
    public $school_id;
    public $is_active;

    public function __construct($request) {
        if($request->get('name')) $this->name = $request->get('name');
        if($request->get('email')) $this->email = $request->get('email');
        if($request->get('phone')) $this->phone = $request->get('phone');
        if($request->get('password')) $this->password = Hash::make($request->get('password'));
        if($request->hasFile('image')) $this->image = $request->file('image');
        if($request->get('subject_id')) $this->subject_id = $request->get('subject_id');
        if($request->get('grade_id')) $this->grade_id = $request->get('grade_id');
        if($request->get('school_id')) $this->school_id = $request->get('school_id');

    }

    public function dataFromRequest()
    {
        $data =  json_decode(json_encode($this), true);
        if($this->name == null) unset($data['name']);
        if($this->email == null) unset($data['email']);
        if($this->phone == null) unset($data['phone']);
        if($this->password == null) unset($data['password']);
        if($this->image == null) unset($data['image']);
        if($this->grade_id == null) unset($data['grade_id']);
        if($this->subject_id == null) unset($data['subject_id']);
        if($this->school_id == null) unset($data['school_id']);

        return $data;
    }
}
