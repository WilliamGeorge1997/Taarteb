<?php


namespace Modules\Notification\Service;

use Modules\User\App\Models\User;
use Illuminate\Support\Facades\DB;
use Modules\Common\Helpers\FCMService;
use Modules\User\Service\UserService;
use Modules\Common\Helpers\UploadHelper;
use Modules\Notification\App\Models\Notification;

class NotificationService
{
    use UploadHelper;
    function findAll()
    {
        return Notification::all();
    }

    function findById($id)
    {
        return Notification::findOrFail($id);
    }

    function findBy($key, $value)
    {
        return Notification::with('notifiable')->where($key, $value)->get();
    }

    function NotificationsInAdminPanel()
    {
        return Notification::groupBy('group_by')->select('id', 'group_by', 'created_at', 'title', DB::raw('count(*) as total'), DB::raw("count(DISTINCT(read_at)) as readCount"))->get();
    }

    function save($data, $user_id, $group_by)
    {
        if (request()->hasFile('image')) {
            $data['image'] = $this->upload(request()->file('image'), 'notification');
        }

        Notification::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'image' => @$data['image'],
            'notifiable_id' => $user_id,
            'notifiable_type' => User::class,
            'group_by' => $group_by
        ]);
    }


    function sendNotificationToUser($data, $user_id, $group_by)
    {
        $this->save($data, $user_id, $group_by);
        $fcm = new FCMService;
        $user_token = (new UserService())->findToken($user_id);
        if ($user_token ?? null)
            $fcm->sendNotification($data, [$user_token]);
    }

    function sendNotificationToAdmins($data, $school_id, $group_by)
    {
        $admins = User::where('school_id', $school_id)
            ->whereIn('role', ['School Manager', 'Financial Director'])
            ->get();

        $tokens = [];
        foreach ($admins as $admin) {
            $this->save($data, $admin->id, $group_by);
            $token = (new UserService())->findToken($admin->id);
            if ($token) {
                $tokens[] = $token;
            }
        }
        if (!empty($tokens)) {
            (new FCMService())->sendNotification($data, $tokens);
        }
    }
}
