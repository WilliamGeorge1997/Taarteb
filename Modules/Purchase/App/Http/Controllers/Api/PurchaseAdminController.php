<?php

namespace Modules\Purchase\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\Purchase\App\Models\Purchase;
use Modules\Purchase\Service\PurchaseService;
use Modules\Notification\Service\NotificationService;

class PurchaseAdminController extends Controller
{
    private $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->middleware('auth:user');
        $this->middleware('role:Super Admin|School Manager|Financial Director');
        $this->purchaseService = $purchaseService;
    }

    public function index(Request $request)
    {
        $relations = ['employee', 'school'];
        $purchases = $this->purchaseService->findAll($request->all(), $relations);
        return returnMessage(true, 'Purchases fetched successfully', $purchases);
    }

    public function accept(Purchase $purchase)
    {
        if ($purchase->status == Purchase::STATUS_ACCEPTED) {
            return returnMessage(false, 'Purchase already accepted', null, 'unprocessable_entity');
        }
        try {
            DB::beginTransaction();
            $purchase->update([
                'status' => Purchase::STATUS_ACCEPTED,
            ]);
            $this->sendNotificationToUser($purchase);
            DB::commit();
            return returnMessage(true, 'Purchase accepted successfully', $purchase);
        } catch (\Exception $e) {
            DB::rollBack();
            return returnMessage(false, 'Failed to accept purchase', null, 'server_error');
        }
    }

    public function reject(Request $request, Purchase $purchase)
    {
        $request->validate([
            'reject_reason' => 'required|string|max:255'
        ]);
        try {
            DB::beginTransaction();
            $purchase->update([
                'status' => Purchase::STATUS_REJECTED,
                'reject_reason' => $request->reject_reason
            ]);
            $this->sendNotificationToUser($purchase);
            DB::commit();
            return returnMessage(true, 'Purchase rejected successfully', $purchase);
        } catch (\Exception $e) {
            DB::rollBack();
            return returnMessage(false, 'Failed to reject purchase', null, 'server_error');
        }
    }

    public function sendNotificationToUser($purchase)
    {
        if ($purchase->status == Purchase::STATUS_ACCEPTED) {
            $data = [
                'title' => 'تم قبول طلب الشراء',
                'description' => 'تم قبول طلب الشراء الخاص بك.',
            ];
        } elseif ($purchase->status == Purchase::STATUS_REJECTED) {
            $data = [
                'title' => 'تم رفض طلب الشراء',
                'description' => 'تم رفض طلب الشراء الخاص بك. السبب: ' . ($purchase->reject_reason ?? 'لم يتم تحديد السبب'),
            ];
        }
        (new NotificationService())->sendNotificationToUser($data, $purchase->user_id, 'purchase');
    }
}
