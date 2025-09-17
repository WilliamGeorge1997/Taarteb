<?php

namespace Modules\Student\Service;

use Modules\Common\Helpers\UploadHelper;
use Modules\Student\App\Models\StudentFee;
use Modules\Student\Service\StudentService;

class StudentFeeService
{
    use UploadHelper;

    function findBySchoolStudents($data = [] , $relations = [])
    {
        $schoolId = auth('user')->user()->school_id;
        $students = (new StudentService())->findBy('school_id', $schoolId)->pluck('id');
        $studentFees = StudentFee::whereIn('student_id', $students)->latest();
        return getCaseCollection($studentFees, $data);
    }
    public function save($data)
    {
        if (request()->hasFile('receipt'))
            $data['receipt'] = $this->upload(request()->file('receipt'), 'student/receipt');
        return StudentFee::create($data);
    }
}
