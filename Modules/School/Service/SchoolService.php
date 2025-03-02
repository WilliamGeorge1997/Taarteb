<?php

namespace Modules\School\Service;

use Modules\Admin\App\Models\Admin;
use Modules\School\App\Models\School;

class SchoolService
{
    function findAll($data = [])
    {
        $schools = School::query()
            ->when($data['name'] ?? null, function ($query) use ($data) {
                $query->where('name', 'like', '%' . $data['name'] . '%');
            })
            ->when($data['email'] ?? null, function ($query) use ($data) {
                $query->where('email', 'like', '%' . $data['email'] . '%');
            })
            ->orderByDesc('created_at');

        return getCaseCollection($schools, $data);
    }

    function findById($id)
    {
        $school = School::findOrFail($id);
        return $school;
    }

    function findBy($key, $value)
    {
        $school = School::where($key, $value)->get();
        return $school;
    }

    function create($data, $managerData)
    {
        $school = School::create($data);
        $managerData['school_id'] = $school->id;
        $schoolManager = Admin::create($managerData);
        $schoolManager->assignRole('School Manager');
        return $school;
    }

    function update($school, $data)
    {
        $school->update($data);
        return $school;
    }

    function delete($school)
    {
        $school->delete();
    }

    function activate($id)
    {
        $school = $this->findById($id);
        $school->is_active = !$school->is_active;
        $school->save();
    }
}
