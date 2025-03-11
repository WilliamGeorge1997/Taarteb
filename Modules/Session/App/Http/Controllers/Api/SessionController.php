<?php

namespace Modules\Session\App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Session\DTO\SessionDto;
use App\Http\Controllers\Controller;
use Modules\Session\App\Models\Session;
use Modules\Session\Service\SessionService;
use Modules\Session\App\resources\SessionResource;
use Modules\Session\App\Http\Requests\SessionRequest;


class SessionController extends Controller
{
   private $sessionService;
   public function __construct(SessionService $sessionService){
      $this->middleware('auth:user');
      $this->middleware('role:Super Admin|School Manager');
      $this->middleware('permission:Index-session|Create-session|Edit-session|Delete-session', ['only' => ['index', 'store']]);
      $this->middleware('permission:Create-session', ['only' => ['store']]);
      $this->middleware('permission:Edit-session', ['only' => ['update', 'activate']]);
      $this->middleware('permission:Delete-session', ['only' => ['destroy']]);
      $this->sessionService = $sessionService;
   }
   public function index(Request $request){
      $data = $request->all();
      $sessions = $this->sessionService->findAll($data);
      return returnMessage(true, 'Sessions Fetched Successfully', SessionResource::collection($sessions)->response()->getData(true));
   }

   public function store(SessionRequest $request){
        $session = $this->sessionService->getSession($request->all());
        if($session){
            return returnMessage(false, 'Session Already Exists', null, 'bad_request');
        }
      try{
         DB::beginTransaction();
         $data = (new SessionDto($request))->dataFromRequest();
         $session = $this->sessionService->create($data);
         DB::commit();
         return returnMessage(true, 'Session Created Successfully', $session);
      }catch(Exception $e){
         DB::rollBack();
         return returnMessage(false, $e->getMessage(), null, 500);
      }
   }

   public function update(SessionRequest $request, Session $session){
      try{
         DB::beginTransaction();
         $data = (new SessionDto($request))->dataFromRequest();
         $session = $this->sessionService->update($session, $data);
         DB::commit();
         return returnMessage(true, 'Session Updated Successfully', $session);
      }catch(Exception $e){
         DB::rollBack();
         return returnMessage(false, $e->getMessage(), null, 500);
      }
   }
}
