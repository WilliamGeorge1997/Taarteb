<?php

namespace Modules\User\Service;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Modules\Common\Helpers\UploadHelper;

class UserService
{
    use UploadHelper;

    public function changePassword($data){
        $user = auth('user')->user();
        $user->update([
            'password' => Hash::make($data['new_password'])
        ]);
    }


    public function updateProfile($data)
    {
        $user = auth('user')->user();
        $gender = @$data['gender'];
        unset($data['gender']);

        if (request()->hasFile('image')) {
            if ($user->image) {
                File::delete(public_path('uploads/user/' . $this->getImageName('user', $user->image)));
            }
            $image = request()->file('image');
            $imageName = $this->upload($image, 'user');
            $data['image'] = $imageName;
        }

        if ($user->hasRole('Teacher')) {
            $user->update($data);
            if ($gender) {
                $user->teacherProfile()->update(['gender' => $gender]);
            }
        } else if ($user->hasRole('School Manager')) {
            $user->update($data);
            if ($data['name']) {
                $user->school()->update(['name' => $data['name']]);
            }
        } else {
            $user->update($data);
        }
    }
}
