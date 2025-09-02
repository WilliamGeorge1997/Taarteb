<?php

namespace Modules\Maintenance\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\Maintenance\App\Models\Maintenance;
use Modules\Maintenance\Service\MaintenanceService;

class MaintenanceAdminController extends Controller
{
    private $maintenanceService;

    public function __construct(MaintenanceService $maintenanceService)
    {
        $this->middleware('auth:user');
        $this->middleware('role:Super Admin|School Manager');
        $this->maintenanceService = $maintenanceService;
    }

    public function index(Request $request)
    {
        $maintenances = $this->maintenanceService->findAll($request->all());
        return returnMessage(true, 'Maintenances fetched successfully', $maintenances);
    }

    public function accept(Maintenance $maintenance)
    {
        if ($maintenance->status == Maintenance::STATUS_ACCEPTED) {
            return returnMessage(false, 'Maintenance already accepted', null, 'unprocessable_entity');
        }
        try {
            DB::beginTransaction();
            $maintenance->update([
                'status' => Maintenance::STATUS_ACCEPTED,
            ]);
            DB::commit();
            return returnMessage(true, 'Maintenance accepted successfully', $maintenance);
        } catch (\Exception $e) {
            DB::rollBack();
            return returnMessage(false, 'Failed to accept maintenance', null, 'server_error');
        }
    }

    public function reject(Request $request, Maintenance $maintenance)
    {
        $request->validate([
            'reject_reason' => 'required|string|max:255'
        ]);
        try {
            DB::beginTransaction();
            $maintenance->update([
                'status' => Maintenance::STATUS_REJECTED,
                'reject_reason' => $request->reject_reason
            ]);
            DB::commit();
            return returnMessage(true, 'Maintenance rejected successfully', $maintenance);
        } catch (\Exception $e) {
            DB::rollBack();
            return returnMessage(false, 'Failed to reject maintenance', null, 'server_error');
        }
    }
}
