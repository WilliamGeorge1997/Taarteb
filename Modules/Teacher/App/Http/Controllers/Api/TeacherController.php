<?php

namespace Modules\Teacher\App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Modules\User\DTO\TeacherDto;
use Modules\User\App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\Teacher\DTO\TeacherProfileDto;
use Modules\Teacher\Service\TeacherService;
use Modules\Teacher\App\Http\Requests\TeacherRequest;

class TeacherController extends Controller
{
   private $teacherService;
   public function __construct(TeacherService $teacherService){
      $this->middleware('auth:user');
      $this->middleware('role:Super Admin|School Manager');
      $this->middleware('permission:Index-teacher|Create-teacher|Edit-teacher|Delete-teacher', ['only' => ['index', 'store']]);
      $this->middleware('permission:Create-teacher', ['only' => ['store']]);
      $this->middleware('permission:Edit-teacher', ['only' => ['update', 'activate']]);
      $this->middleware('permission:Delete-teacher', ['only' => ['destroy']]);
      $this->teacherService = $teacherService;
   }
   public function index(Request $request){
      $data = $request->all();
      $teachers = $this->teacherService->findAll($data);
      return returnMessage(true, 'Teachers Fetched Successfully', $teachers);
   }

   public function store(TeacherRequest $request){
        try{
         DB::beginTransaction();
         $teacherData = (new TeacherDto($request))->dataFromRequest();
         $teacherProfileData = (new TeacherProfileDto($request))->dataFromRequest();
         $teacher = $this->teacherService->create($teacherData, $teacherProfileData);
         DB::commit();
         return returnMessage(true, 'Teacher Created Successfully', $teacher);
      }catch(Exception $e){
         DB::rollBack();
         return returnMessage(false, $e->getMessage(), null, 500);
      }
   }

   public function update(TeacherRequest $request, User $teacher){
      try{
         DB::beginTransaction();
         $data = (new TeacherDto($request))->dataFromRequest();
         $teacher = $this->teacherService->update($teacher, $data);
         DB::commit();
         return returnMessage(true, 'Teacher Updated Successfully', $teacher);
      }catch(Exception $e){
         DB::rollBack();
         return returnMessage(false, $e->getMessage(), null, 500);
      }
   }
}
