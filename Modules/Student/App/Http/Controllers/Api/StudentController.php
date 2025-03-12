<?php

namespace Modules\Student\App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Student\DTO\StudentDto;
use App\Http\Controllers\Controller;
use Modules\Student\App\Models\Student;
use Modules\Student\Service\StudentService;
use Modules\Student\App\resources\StudentResource;
use Modules\Student\App\Http\Requests\StudentRequest;
use Modules\Student\App\Http\Requests\UpgradeRequest;
use Modules\Student\App\Http\Requests\GraduateRequest;

class StudentController extends Controller
{
   private $studentService;
   public function __construct(StudentService $studentService){
      $this->middleware('auth:user');
      $this->middleware('role:Super Admin|School Manager');
      $this->middleware('permission:Index-student|Create-student|Edit-student|Delete-student', ['only' => ['index', 'store']]);
      $this->middleware('permission:Create-student', ['only' => ['store']]);
      $this->middleware('permission:Edit-student', ['only' => ['update', 'activate']]);
      $this->middleware('permission:Delete-student', ['only' => ['destroy']]);
      $this->middleware('permission:Index-student-upgrade|Create-student-upgrade|Edit-student-upgrade|Delete-student-upgrade', ['only' => ['getStudentsToUpgrade','upgrade']]);
      $this->middleware('permission:Index-student-graduation|Create-student-graduation|Edit-student-graduation|Delete-student-graduation', ['only' => ['getStudentsToGraduate','graduate']]);
      $this->studentService = $studentService;
   }
   public function index(Request $request){
      $data = $request->all();
      $students = $this->studentService->findAll($data);
      return returnMessage(true, 'Students Fetched Successfully', StudentResource::collection($students)->response()->getData(true));
   }

   public function store(StudentRequest $request){
      try{
         DB::beginTransaction();
         $data = (new StudentDto($request))->dataFromRequest();
         $student = $this->studentService->create($data);
         DB::commit();
         return returnMessage(true, 'Student Created Successfully', $student);
      }catch(Exception $e){
         DB::rollBack();
         return returnMessage(false, $e->getMessage(), null, 500);
      }
   }

   public function update(StudentRequest $request, Student $student){
      try{
         DB::beginTransaction();
         $data = (new StudentDto($request))->dataFromRequest();
         $student = $this->studentService->update($student, $data);
         DB::commit();
         return returnMessage(true, 'Student Updated Successfully', $student);
      }catch(Exception $e){
         DB::rollBack();
         return returnMessage(false, $e->getMessage(), null, 500);
      }
   }

   public function getStudentsToGraduate(Request $request){
      $data = $request->all();
      $students = $this->studentService->getStudentsToGraduate($data);
      return returnMessage(true, 'Students Fetched Successfully', StudentResource::collection($students)->response()->getData(true));
   }

   public function graduate(GraduateRequest $request){
      try{
         DB::beginTransaction();
         $this->studentService->graduate($request->validated());
         DB::commit();
         return returnMessage(true, 'Students Graduated Successfully');
      }catch(Exception $e){
         DB::rollBack();
         return returnMessage(false, $e->getMessage(), null, 500);
      }
   }
    public function getStudentsToUpgrade(Request $request, $class_id)
    {
        $data = $request->all();
        $students = $this->studentService->getStudentsToUpgrade($data, $class_id);
        return returnMessage(true, 'Students Fetched Successfully', StudentResource::collection($students)->response()->getData(true));
    }
    public function upgrade(UpgradeRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->studentService->upgrade($request->validated());
            DB::commit();
            return returnMessage(true, 'Students Upgraded Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return returnMessage(false, $e->getMessage(), null, 500);
        }
    }
}
