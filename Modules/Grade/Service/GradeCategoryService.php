<?php

namespace Modules\Grade\Service;

use Modules\Grade\App\Models\GradeCategory;

class GradeCategoryService
{
    function findAll($data = [])
    {
        $gradeCategories = GradeCategory::query()
            ->when($data['name'] ?? null, function ($query) use ($data) {
                $query->where('name', 'like', '%' . $data['name'] . '%');
            })
            ->with('school')
            ->available()
            ->orderByDesc('created_at');
        return getCaseCollection($gradeCategories, $data);
    }

    function findById($id)
    {
        $gradeCategory = GradeCategory::findOrFail($id);
        return $gradeCategory;
    }

    function findBy($key, $value)
    {

        $gradeCategory = GradeCategory::where($key, $value)->get();
        return $gradeCategory;
    }

    function create($data)
    {
        if (auth('user')->user()->hasRole('School Manager'))
            $data['school_id'] = auth('user')->user()->school_id;
        $gradeCategory = GradeCategory::create($data);
        return $gradeCategory;
    }

    function update($gradeCategory, $data)
    {
        $gradeCategory->update($data);
        return $gradeCategory;
    }

    function delete($gradeCategory)
    {
        $gradeCategory->delete();
    }
}
