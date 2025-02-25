<?php

namespace Modules\Teacher\App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Teacher\DTO\TeacherDto;
use App\Http\Controllers\Controller;
use Modules\Teacher\App\Models\Teacher;
use Modules\Teacher\Service\TeacherService;
use Modules\Teacher\App\Http\Requests\TeacherRequest;

class TeacherController extends Controller
{
   private $teacherService;
   public function __construct(TeacherService $teacherService){
      $this->middleware('auth:admin');
      $this->middleware('role:Super Admin,School Manager');
      $this->middleware('permission:Index-teacher|Create-teacher|Edit-teacher|Delete-teacher', ['only' => ['index', 'store']]);
      $this->middleware('permission:Create-teacher', ['only' => ['create', 'store']]);
      $this->middleware('permission:Edit-teacher', ['only' => ['edit', 'update', 'activate']]);
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
         $data = (new TeacherDto($request))->dataFromRequest();
         $teacher = $this->teacherService->create($data);
         DB::commit();
         return returnMessage(true, 'Teacher Created Successfully', $teacher);
      }catch(Exception $e){
         DB::rollBack();
         return returnMessage(false, $e->getMessage(), null, 500);
      }
   }

   public function update(TeacherRequest $request, Teacher $teacher){
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
