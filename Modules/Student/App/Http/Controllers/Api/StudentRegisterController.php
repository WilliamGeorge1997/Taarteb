<?php

namespace Modules\Student\App\Http\Controllers\Api;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\User\DTO\StudentUserDto;
use Modules\User\Service\UserService;
use Modules\Grade\Service\GradeService;
use Modules\School\Service\SchoolService;
use Modules\Student\DTO\StudentParentDto;
use Modules\Student\DTO\StudentRegisterDto;
use Modules\Student\Service\StudentService;
use Modules\User\App\resources\UserResource;
use Modules\Grade\Service\GradeCategoryService;
use Modules\Student\App\Http\Requests\StudentRegisterRequest;

class StudentRegisterController extends Controller
{
    private $studentService;
    private $userService;
    public function __construct(StudentService $studentService, UserService $userService)
    {
        $this->studentService = $studentService;
        $this->userService = $userService;
    }

    public function register(StudentRegisterRequest $request)
    {
        try {
            DB::beginTransaction();
            $studentUserData = (new StudentUserDto($request))->dataFromRequest();
            $studentUser = $this->userService->saveStudentUser($studentUserData);
            $data = (new StudentRegisterDto($request))->dataFromRequest();
            $studentParentData = (new StudentParentDto($request))->dataFromRequest();
            $student = $this->studentService->create($data, $studentUser, $studentParentData);
            $token = auth('user')->login($studentUser);
            DB::commit();
            return $this->respondWithToken($token);
        } catch (Exception $e) {
            DB::rollBack();
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function schools()
    {
        $schools = (new SchoolService)->active();
        return returnMessage(true, 'Schools Fetched Successfully', $schools);
    }

    public function gradeCategories($school_id)
    {
        $gradeCategories = (new GradeCategoryService)->findBy('school_id', $school_id);
        return returnMessage(true, 'Grade Categories Fetched Successfully', $gradeCategories);
    }

    public function grades($grade_category_id)
    {
        $grades = (new GradeService)->findBy('grade_category_id', $grade_category_id);
        return returnMessage(true, 'Grades Fetched Successfully', $grades);
    }
    protected function respondWithToken($token)
    {
        return returnMessage(true, 'Successfully Registered', [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('user')->factory()->getTTL() * 60,
            'user' => new UserResource(auth('user')->user()),
        ]);
    }

}
