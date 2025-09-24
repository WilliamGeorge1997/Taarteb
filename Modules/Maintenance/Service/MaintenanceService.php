<?php

namespace Modules\Maintenance\Service;

use Modules\Common\Helpers\UploadHelper;
use Modules\Maintenance\App\Models\Maintenance;
use Illuminate\Support\Facades\File;
class MaintenanceService
{
    use UploadHelper;
    function findAll($data = [], $relations = [])
    {
        $maintenances = Maintenance::query()
            ->available()
            ->with($relations)
            ->latest();
        return getCaseCollection($maintenances, $data);
    }

    function findMyMaintenances($data = [], $relations = [])
    {
        $maintenances = Maintenance::query()
            ->with($relations)
            ->where('user_id', auth('user')->id())
            ->latest();
        return getCaseCollection($maintenances, $data);
    }

    function findById($id, $relations = [])
    {
        return Maintenance::with($relations)->findOrFail($id);
    }

    function findBy($key, $value, $relations = [])
    {
        return Maintenance::where($key, $value)->with($relations)->get();
    }

    function create($data)
    {
        if (request()->hasFile('image')) {
            $data['image'] = $this->upload(request()->file('image'), 'maintenance');
        }
        $maintenance = Maintenance::create($data);
        return $maintenance;
    }

    function update($maintenance, $data)
    {
        if (request()->hasFile('image')) {
            if ($maintenance->image) {
                File::delete(public_path('uploads/maintenance/' . $this->getImageName('maintenance', $maintenance->image)));
            }
            $data['image'] = $this->upload(request()->file('image'), 'maintenance');
        }
        $maintenance->update($data);
        return $maintenance;
    }

    function totalCost()
    {
        return Maintenance::available()->where('status', Maintenance::STATUS_ACCEPTED)->sum('price');
    }
}
