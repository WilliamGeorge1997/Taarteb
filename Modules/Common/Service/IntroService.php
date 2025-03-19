<?php

namespace Modules\Common\Service;

use Modules\Common\App\Models\Intro;
use Illuminate\Support\Facades\File;
use Modules\Common\Helpers\UploadHelper;
class IntroService
{
    use UploadHelper;
    public function findAll($data = [])
    {
        return Intro::query()->with('details')->get();
    }

    public function create($data)
    {
        if (request()->hasFile('image')) {
            $data['image'] = $this->upload(request()->file('image'), 'intro');
        }
        $intro = Intro::create($data);
        if (!empty($data['details'])) {
            $details = $this->processDetailsData($data['details'], $data['section']);
            $intro->details()->createMany($details);
        }
        return $intro->fresh()->load('details');
    }
    
    private function processDetailsData($details, $section)
    {
        $processedDetails = [];
        
        foreach ($details as $index => $detail) {
            if (request()->hasFile("details.{$index}.image")) {
                $detail['image'] = $this->upload(request()->file("details.{$index}.image"), 'intro');
            }
            $detail['section'] = $section;
            $processedDetails[] = $detail;
        }
        return $processedDetails;
    }

    public function update($intro, $data)
    {
        if (request()->hasFile('image')) {
            File::delete(public_path('uploads/intro/' . $this->getImageName('intro', $intro->image)));
            $data['image'] = $this->upload(request()->file('image'), 'intro');
        }
        return $intro->update($data);
    }

    public function delete($intro)
    {
        File::delete(public_path('uploads/intro/' . $this->getImageName('intro', $intro->image)));
        return $intro->delete();
    }
}
