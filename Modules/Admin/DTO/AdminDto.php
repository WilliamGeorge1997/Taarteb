<?php


namespace Modules\Admin\DTO;

use Illuminate\Support\Facades\Hash;


class AdminDto
{
    public $name;
    public $email;
    public $password;

    public function __construct($request) {
        $this->name = $request->get('manager_name');
        $this->email = $request->get('manager_email');
        $this->password = Hash::make($request->get('manager_password'));
    }

    public function dataFromRequest()
    {
        $data =  json_decode(json_encode($this), true);
        if($this->name == null) unset($data['manager_name']);
        if($this->email == null) unset($data['manager_email']);
        if($this->password == null) unset($data['manager_password']);
        return $data;
    }
}
