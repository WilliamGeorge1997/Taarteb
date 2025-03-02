<?php

namespace Modules\Student\Service;

use Illuminate\Support\Facades\File;
use Modules\Student\App\Models\Student;
use Modules\Common\Helpers\UploadHelper;

class StudentService
{
    use UploadHelper;
    function findAll($data = [])
    {
        $students = Student::query()
        ->when($data['name'] ?? null, function($query) use ($data){
            $query->where('name', 'like', '%'.$data['name'].'%');
        })
        ->when($data['email'] ?? null, function($query) use ($data){
            $query->where('email', 'like', '%'.$data['email'].'%');
        })
        ->available()->orderByDesc('created_at');
        return getCaseCollection($students, $data);
    }

    function findById($id)
    {
        $student = Student::available()->findOrFail($id);
        return $student;
    }

    function findBy($key, $value)
    {
        $student = Student::available()->where($key, $value)->get();
        return $student;
    }

    function create($data)
    {
        if(auth('admin')->user()->hasRole('School Manager')) $data['school_id'] = auth('admin')->user()->school_id;
        $student = Student::create($data);
        return $student;
    }

    function update($student, $data)
    {
        $student->update($data);
        return $student;
    }

    function delete($student)
    {
        $student->delete();
    }

    function activate($id)
    {
        $student = $this->findById($id);
        $student->is_active = !$student->is_active;
        $student->save();
    }
}
