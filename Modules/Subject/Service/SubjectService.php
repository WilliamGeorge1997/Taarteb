<?php

namespace Modules\Subject\Service;

use Modules\Subject\App\Models\Subject;

class SubjectService
{
    function findAll($data = [])
    {
        $subjects = Subject::query()
            ->when($data['name'] ?? null, function ($query) use ($data) {
                $query->where('name', 'like', '%' . $data['name'] . '%');
            })
            ->when($data['semester'] ?? null, function ($query) use ($data) {
                $query->where('semester', $data['semester']);
            })
            ->with('grade', 'school')
            ->available()
            ->orderByDesc('created_at');
        return getCaseCollection($subjects, $data);
    }

    function findById($id)
    {
        $subject = Subject::findOrFail($id);
        return $subject;
    }

    function findBy($key, $value)
    {
        $subject = Subject::where($key, $value)->get();
        return $subject;
    }

    function create($data)
    {
        $subject = Subject::create($data);
        return $subject;
    }

    function update($subject, $data)
    {
        $subject->update($data);
        return $subject;
    }

    function delete($subject)
    {
        $subject->delete();
    }

    function getSubjectsByGradeId($grade)
    {
       return Subject::available()->with(['grade', 'school'])->where('grade_id', $grade->id)->orderByDesc('created_at')->get();
    }
}
