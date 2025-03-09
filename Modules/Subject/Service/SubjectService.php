<?php

namespace Modules\Subject\Service;

use Modules\Subject\App\Models\Subject;

class SubjectService
{
    function findAll($data = [])
    {
        $grades = Subject::query()
            ->when($data['name'] ?? null, function ($query) use ($data) {
                $query->where('name', 'like', '%' . $data['name'] . '%');
            })
            ->available()
            ->orderByDesc('created_at');
        return getCaseCollection($grades, $data);
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
        if (auth('user')->user()->hasRole('School Manager'))
            $data['school_id'] = auth('user')->user()->school_id;
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
}
