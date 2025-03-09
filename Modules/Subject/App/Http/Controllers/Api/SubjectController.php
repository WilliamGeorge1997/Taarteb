<?php

namespace Modules\Subject\App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Modules\Grade\DTO\GradeDto;
use Illuminate\Support\Facades\DB;
use Modules\Grade\App\Models\Grade;
use App\Http\Controllers\Controller;
use Modules\Grade\Service\GradeService;
use Modules\Subject\Service\SubjectService;
use Modules\Grade\App\resources\GradeResource;
use Modules\Grade\App\Http\Requests\GradeRequest;


class SubjectController extends Controller
{
    protected $subjectService;
    public function __construct(SubjectService $subjectService)
    {
        $this->middleware('auth:user');
        $this->middleware('role:Super Admin|School Manager');
        $this->middleware('permission:Index-subject|Create-subject|Edit-subject|Delete-subject', ['only' => ['index', 'store']]);
        $this->middleware('permission:Create-subject', ['only' => ['store']]);
        $this->middleware('permission:Edit-subject', ['only' => ['update', 'activate']]);
        $this->middleware('permission:Delete-subject', ['only' => ['destroy']]);
        $this->subjectService = $subjectService;
    }

    // public function subjectsByGradeId(Grade $grade)
    // {
    //     return returnMessage(true, 'Subjects fetched successfully', SubjectResource::collection($grade->subjects()->get())->response()->getData(true));
    // }


    public function index(Request $request)
    {
        $data = $request->all();
        $subjects = $this->subjectService->findAll($data);
        return returnMessage(true, 'Subjects fetched successfully', SubjectResource::collection($subjects)->response()->getData(true));
    }

    public function store(SubjectRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = (new SubjectDto($request))->dataFromRequest();
            $subject = $this->subjectService->create($data);
            DB::commit();
            return returnMessage(true, 'Grade Created Successfully', $grade);
        } catch (Exception $e) {
            DB::rollBack();
            return returnMessage(false, $e->getMessage(), null, 500);
        }
    }

    public function update(GradeRequest $request, Grade $grade)
    {
        try {
            DB::beginTransaction();
            $data = (new GradeDto($request))->dataFromRequest();
            $grade = $this->gradeService->update($grade, $data);
            DB::commit();
            return returnMessage(true, 'Grade Updated Successfully', $grade);
        } catch (Exception $e) {
            DB::rollBack();
            return returnMessage(false, $e->getMessage(), null, 500);
        }
    }

}
