<?php

namespace Modules\Teacher\Service;

use Modules\User\App\Models\User;
use Illuminate\Support\Facades\File;
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

    function create($teacherData, $teacherProfileData)
    {
        if (request()->hasFile('image')) {
            $image = request()->file('image');
            $imageName = $this->upload($image, 'user');
            $teacherProfileData['image'] = $imageName;
        }
        if (auth('user')->user()->hasRole('School Manager'))
            $teacherProfileData['school_id'] = auth('user')->user()->school_id;
        $teacher = User::create($teacherData);
        $teacher->assignRole('Teacher');
        $teacher->teacherProfile()->create($teacherProfileData);
        return $teacher;
    }

    function update($teacher, $data)
    {
        if (request()->hasFile('image')) {
            File::delete(public_path('uploads/user/' . $this->getImageName('user', $teacher->image)));
            $image = request()->file('image');
            $imageName = $this->upload($image, 'user');
            $data['image'] = $imageName;
        }
        $teacher->update($data);
        return $teacher;
    }

    function delete($teacher)
    {
        File::delete(public_path('uploads/user/' . $this->getImageName('user', $teacher->image)));
        $teacher->delete();
    }

    function activate($id)
    {
        $teacher = $this->findById($id);
        $teacher->is_active = !$teacher->is_active;
        $teacher->save();
    }
}
