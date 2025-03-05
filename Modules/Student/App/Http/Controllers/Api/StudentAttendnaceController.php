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

class StudentAttendnaceController extends Controller
{
   private $studentService;
   public function __construct(StudentService $studentService){
      $this->middleware('auth:user');
      $this->middleware('role:Teacher');
      $this->middleware('permission:Index-attendance|Create-attendance|Edit-attendance|Delete-attendance', ['only' => ['index', 'store']]);
      $this->middleware('permission:Create-attendance', ['only' => ['store']]);
      $this->middleware('permission:Edit-attendance', ['only' => ['update', 'activate']]);
      $this->middleware('permission:Delete-attendance', ['only' => ['destroy']]);
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
}
