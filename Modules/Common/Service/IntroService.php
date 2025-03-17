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
        return Intro::query()->get();
    }

    public function create($data)
    {
        if (request()->hasFile('image')) {
            $image = request()->file('image');
            $imageName = $this->upload($image, 'intro');
            $data['image'] = $imageName;
        }
        $intro = Intro::create($data);
        return $intro;
    }

    public function update($intro, $data)
    {
        if (request()->hasFile('image')) {
            File::delete(public_path('uploads/intro/' . $this->getImageName('intro', $intro->image)));
            $image = request()->file('image');
            $imageName = $this->upload($image, 'intro');
            $data['image'] = $imageName;
        }
        return $intro->update($data);
    }

    public function delete($intro)
    {
        File::delete(public_path('uploads/intro/' . $this->getImageName('intro', $intro->image)));
        return $intro->delete();
    }
}
