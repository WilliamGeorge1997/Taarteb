<?php

namespace Modules\School\Service;

use Modules\School\App\Models\School;

class SchoolService
{
    function findAll()
    {
        $schools = School::all();
        return $schools;
    }

    function findById($id)
    {
        $school = School::find($id);
        return $school;
    }

    function findBy($key, $value)
    {
        $school = School::where($key, $value)->get();
        return $school;
    }

    function create($data)
    {
        return School::create($data);
    }

    function update($school, $data)
    {
        $school->update($data);
        return $school;
    }

    function delete($id)
    {
        $school = $this->findById($id);
        $school->delete();
    }

    function activate($id)
    {
        $school = $this->findById($id);
        $school->is_active = !$school->is_active;
        $school->save();
    }
}
