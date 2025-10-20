<?php

namespace Modules\Student\Service;

use Illuminate\Support\Facades\File;
use Modules\Class\App\Models\Classroom;
use Modules\Student\App\Models\Student;
use Modules\Common\Helpers\UploadHelper;

class StudentService
{
    use UploadHelper;
    function findAll($data = [])
    {
        $students = Student::query()
            ->when($data['name'] ?? null, function ($query) use ($data) {
                $query->where('name', 'like', '%' . $data['name'] . '%');
            })
            ->when($data['email'] ?? null, function ($query) use ($data) {
                $query->where('email', 'like', '%' . $data['email'] . '%');
            })
            ->when($data['grade_id'] ?? null, function ($query) use ($data) {
                $query->where('grade_id', $data['grade_id']);
            })
            ->when($data['grade_category_id'] ?? null, function ($query) use ($data) {
                $query->whereHas('grade', function ($query) use ($data) {
                    $query->where('grade_category_id', $data['grade_category_id']);
                });
            })
            ->availableAll()
            ->with('grade.gradeCategory', 'school')
            ->orderByDesc('id');
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

    function create($data, $studentUser, $studentParentData)
    {
        if (request()->hasFile('application_form')) {
            $data['application_form'] = $this->uploadFile(request()->file('application_form'), 'student/application_form');
        }
        if (request()->hasFile('parent_identity_card_image')) {
            $data['parent_identity_card_image'] = $this->uploadFile(request()->file('parent_identity_card_image'), 'student/application_form');
        }
        if (request()->hasFile('student_residence_card_image')) {
            $data['student_residence_card_image'] = $this->uploadFile(request()->file('student_residence_card_image'), 'student/application_form');
        }
        if (request()->hasFile('image')) {
            $data['image'] = $this->uploadFile(request()->file('image'), 'student/application_form');
        }
        if (request()->hasFile('student_passport_image')) {
            $data['student_passport_image'] = $this->uploadFile(request()->file('student_passport_image'), 'student/application_form');
        }
        if (request()->hasFile('student_birth_certificate_image')) {
            $data['student_birth_certificate_image'] = $this->uploadFile(request()->file('student_birth_certificate_image'), 'student/application_form');
        }
        if (request()->hasFile('student_health_card_image')) {
            $data['student_health_card_image'] = $this->uploadFile(request()->file('student_health_card_image'), 'student/application_form');
        }
        if (request()->hasFile('home_map_image')) {
            $data['home_map_image'] = $this->uploadFile(request()->file('home_map_image'), 'student/application_form');
        }
        $student = $studentUser->student()->create($data);
        $student->parent()->create($studentParentData);
        return $student;
    }

    function update($student, $data)
    {
        if (request()->hasFile('application_form')) {
            File::delete(public_path('uploads/student/application_form/' . $student->application_form));
            $data['application_form'] = $this->uploadFile(request()->file('application_form'), 'student/application_form');
        }
        $student->update($data);
        if ($student->user_id) {
            $student->user->update([
                'name' => $student->name,
                'email' => $student->email,
            ]);
        }
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

    function getStudentsToGraduate($data = [])
    {
        $students = Student::available()->where('is_graduated', 0)->whereHas('grade', function ($query) {
            $query->where('is_final', 1);
        });
        return getCaseCollection($students, $data);
    }

    function graduate($studentsIds)
    {
        Student::whereIn('id', $studentsIds)->update(['is_graduated' => 1]);
    }

    function getStudentsToUpgrade($data = [], $class_id)
    {
        $students = Student::query()
            ->available()
            ->where('is_graduated', 0)
            ->where('class_id', $class_id);
        return getCaseCollection($students, $data);
    }
    function upgrade($data)
    {
        $class = Classroom::where('id', $data['class_id'])->first();
        Student::whereIn('id', $data['student_ids'])
            ->update([
                'class_id' => $class->id,
                'grade_id' => $class->grade_id
            ]);
    }
}
