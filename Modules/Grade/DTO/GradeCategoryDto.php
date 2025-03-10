<?php


namespace Modules\Grade\DTO;

class GradeCategoryDto
{
    public array $name;
    public $school_id;

    public function __construct($request)
    {
        if ($request->get('name')) {
            $this->name = $request->get('name');
        }

        if (auth('user')->user()->hasRole('Super Admin')) {
            if ($request->get('school_id')) {
                $this->school_id = $request->get('school_id');
            }
        } else if (auth('user')->user()->hasRole('School Manager')) {
            if ($request->isMethod('POST')) {
                $this->school_id = auth('user')->user()->school_id;
            }
        }
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);
        if ($this->name == null)
            unset($data['name']);
        if ($this->school_id == null)
            unset($data['school_id']);
        return $data;
    }
}
