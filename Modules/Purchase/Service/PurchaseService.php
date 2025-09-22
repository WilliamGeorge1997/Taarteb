<?php

namespace Modules\Purchase\Service;

use Modules\Common\Helpers\UploadHelper;
use Modules\Purchase\App\Models\Purchase;
use Illuminate\Support\Facades\File;
class PurchaseService
{
    use UploadHelper;
    function findAll($data = [], $relations = [])
    {
        $purchases = Purchase::query()
            ->available()
            ->with($relations)
            ->latest();
        return getCaseCollection($purchases, $data);
    }

    function findMyPurchases($data = [], $relations = [])
    {
        $purchases = Purchase::query()
            ->with($relations)
            ->where('user_id', auth('user')->id())
            ->latest();
        return getCaseCollection($purchases, $data);
    }

    function findById($id, $relations = [])
    {
        return Purchase::with($relations)->findOrFail($id);
    }

    function findBy($key, $value, $relations = [])
    {
        return Purchase::where($key, $value)->with($relations)->get();
    }

    function create($data)
    {
        if (request()->hasFile('image')) {
            $data['image'] = $this->upload(request()->file('image'), 'purchase');
        }
        $purchase = Purchase::create($data);
        return $purchase;
    }

    function update($purchase, $data)
    {
        if (request()->hasFile('image')) {
            if ($purchase->image) {
                File::delete(public_path('uploads/purchase/' . $this->getImageName('purchase', $purchase->image)));
            }
            $data['image'] = $this->upload(request()->file('image'), 'purchase');
        }
        $purchase->update($data);
        return $purchase;
    }
}
