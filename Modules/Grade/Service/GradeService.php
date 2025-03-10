<?php

namespace Modules\Grade\Service;

use Modules\Grade\App\Models\Grade;

class GradeService
{
    function findAll($data = [])
    {
        $grades = Grade::query()
            ->when($data['name'] ?? null, function ($query) use ($data) {
                $query->where('name', 'like', '%' . $data['name'] . '%');
            })
            ->with(['gradeCategory', 'school'])
            ->available()
            ->orderByDesc('created_at');
        return getCaseCollection($grades, $data);
    }

    function findById($id)
    {
        $grade = Grade::findOrFail($id);
        return $grade;
    }

    function findBy($key, $value)
    {
        $grade = Grade::where($key, $value)->get();
        return $grade;
    }

    function create($data)
    {
        $grade = Grade::create($data);
        return $grade;
    }

    function update($grade, $data)
    {
        $grade->update($data);
        return $grade;
    }

    function delete($grade)
    {
        $grade->delete();
    }
}