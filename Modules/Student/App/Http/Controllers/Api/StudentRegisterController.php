<?php

namespace Modules\Student\App\Http\Controllers\Api;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\User\DTO\StudentUserDto;
use Modules\User\Service\UserService;
use Modules\Student\DTO\StudentRegisterDto;
use Modules\Student\Service\StudentService;
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
            $data = (new StudentRegisterDto($request, $studentUser->id))->dataFromRequest();
            $student = $this->studentService->create($data);
            DB::commit();
            return returnMessage(true, 'Student Registered Successfully', $student);
        } catch (Exception $e) {
            DB::rollBack();
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
