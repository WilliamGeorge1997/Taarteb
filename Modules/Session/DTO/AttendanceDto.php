<?php


namespace Modules\Session\DTO;

class AttendanceDto
{
    public $student_id;
    public $session_id;
    public $is_present;

    public function __construct($request)
    {
        if ($request->get('student_id'))
            $this->student_id = $request->get('student_id');
        if ($request->get('session_id'))
            $this->session_id = $request->get('session_id');
        if ($request->get('is_present')){
            if($request->get('is_present') == 1)
                $this->is_present = 1;
            else
                $this->is_present = 0;
        }

    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);
        if ($this->student_id == null)
            unset($data['student_id']);
        if ($this->session_id == null)
            unset($data['session_id']);
        if ($this->is_present == null)
            unset($data['is_present']);
        return $data;
    }
}

