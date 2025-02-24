<?php

namespace Modules\School\App\Http\Controllers\Api;

use Modules\School\DTO\SchoolDto;
use App\Http\Controllers\Controller;
use Modules\School\App\Models\School;
use Illuminate\Support\Facades\Request;
use Modules\School\Service\SchoolService;
use Modules\School\App\Http\Requests\SchoolRequest;

class SchoolController extends Controller
{
   private $schoolService;
   public function __construct(SchoolService $schoolService){
      $this->middleware('auth:admin');
      $this->schoolService = $schoolService;
      $this->middleware('permission:Index-school|Create-school|Edit-school|Delete-school', ['only' => ['index', 'store']]);
      $this->middleware('permission:Create-school', ['only' => ['create', 'store']]);
      $this->middleware('permission:Edit-school', ['only' => ['edit', 'update', 'activate']]);
      $this->middleware('permission:Delete-school', ['only' => ['destroy']]);
   }
   public function index(Request $request){
   //  $data = $this->schoolService->findAll($request);
   }

   public function store(SchoolRequest $request){
      $data = (new SchoolDto($request))->dataFromRequest();
      $school = $this->schoolService->create($data);
      return returnMessage(true, 'School Created Successfully', $school);
   }

   public function update(SchoolRequest $request, School $school){
      $data = (new SchoolDto($request))->dataFromRequest();
      $school = $this->schoolService->update($school, $data);
      return returnMessage(true, 'School Updated Successfully', $school);
   }
}
