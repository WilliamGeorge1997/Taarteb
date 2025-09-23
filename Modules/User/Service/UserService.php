<?php

namespace Modules\User\Service;

use Modules\User\App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Modules\Common\Helpers\UploadHelper;

class UserService
{
    use UploadHelper;

    public function changePassword($data)
    {
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

    public function saveStudentUser($data)
    {
        $user = User::create($data);
        $user->assignRole('Student');
        return $user;
    }
    public function saveEmployeeUser($data)
    {
        $role = Role::find($data['role_id']);
        $data['role'] = $role->name;
        $user = User::create($data);
        $user->assignRole($role->name);
        return $user;
    }

    public function findToken($user_id)
    {
        $user = User::find($user_id);
        return $user->fcm_token;
    }
}
