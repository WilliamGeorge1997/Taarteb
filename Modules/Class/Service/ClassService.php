<?php

namespace Modules\Class\Service;

use Modules\Class\App\Models\Classroom;


class ClassService
{
    function findAll($data = [])
    {
        $classes = Classroom::query()->available()->with(['school:id,name', 'grade:id,name'])->orderByDesc('created_at');
        return getCaseCollection($classes, $data);
    }

    function findById($id)
    {
        return Classroom::findOrFail($id);
    }

    function findBy($key, $value)
    {
        return Classroom::where($key, $value)->get();
    }

    function create($data)
    {
        $class = Classroom::create($data);
        return $class;
    }

    function update($class, $data)
    {
        $class->update($data);
        return $class;
    }

    function delete($class)
    {
        $class->delete();
    }

    function activate($id)
    {
        $class = $this->findById($id);
        $class->is_active = !$class->is_active;
        $class->save();
    }
}
