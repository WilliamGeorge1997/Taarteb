<?php

namespace Modules\Class\Service;

use Modules\Class\App\Models\Classroom;


class ClassService
{
    function findAll($data = [])
    {
        $classes = Classroom::query()->orderByDesc('created_at');
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
        return Classroom::create($data);
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
