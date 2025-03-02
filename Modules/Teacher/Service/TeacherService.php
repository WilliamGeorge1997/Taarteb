<?php

namespace Modules\Teacher\Service;

use Illuminate\Support\Facades\File;
use Modules\Teacher\App\Models\Teacher;
use Modules\Common\Helpers\UploadHelper;

class TeacherService
{
    use UploadHelper;
    function findAll($data = [])
    {
        $teachers = Teacher::query()
            ->when($data['name'] ?? null, function ($query) use ($data) {
                $query->where('name', 'like', '%' . $data['name'] . '%');
            })
            ->when($data['email'] ?? null, function ($query) use ($data) {
                $query->where('email', 'like', '%' . $data['email'] . '%');
            })
            ->available()->orderByDesc('created_at');
        return getCaseCollection($teachers, $data);
    }

    function findById($id)
    {
        $teacher = Teacher::available()->findOrFail($id);
        return $teacher;
    }

    function findBy($key, $value)
    {
        $teacher = Teacher::available()->where($key, $value)->get();
        return $teacher;
    }

    function create($data)
    {
        if (request()->hasFile('image')) {
            $image = request()->file('image');
            $imageName = $this->upload($image, 'teacher');
            $data['image'] = $imageName;
        }
        if (auth('admin')->user()->hasRole('School Manager'))
            $data['school_id'] = auth('admin')->user()->school_id;
        $teacher = Teacher::create($data);
        return $teacher;
    }

    function update($teacher, $data)
    {
        if (request()->hasFile('image')) {
            File::delete(public_path('uploads/teacher/' . $this->getImageName('teacher', $teacher->image)));
            $image = request()->file('image');
            $imageName = $this->upload($image, 'teacher');
            $data['image'] = $imageName;
        }
        $teacher->update($data);
        return $teacher;
    }

    function delete($teacher)
    {
        File::delete(public_path('uploads/teacher/' . $this->getImageName('teacher', $teacher->image)));
        $teacher->delete();
    }

    function activate($id)
    {
        $teacher = $this->findById($id);
        $teacher->is_active = !$teacher->is_active;
        $teacher->save();
    }
}
