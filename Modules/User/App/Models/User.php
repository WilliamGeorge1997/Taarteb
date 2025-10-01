<?php

namespace Modules\User\App\Models;

use Spatie\Activitylog\LogOptions;
use Modules\School\App\Models\School;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Modules\Student\App\Models\Student;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Activitylog\Traits\LogsActivity;
use Modules\Teacher\App\Models\TeacherProfile;
use Modules\Notification\App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, HasRoles, LogsActivity;

    protected $fillable = ['name', 'email', 'phone', 'password', 'role', 'is_active', 'image', 'school_id', 'fcm_token'];
    protected $hidden = ['password', 'remember_token'];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('User')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }
    /**
     * The attributes that are mass assignable.
     */


    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }
    public function getImageAttribute($value)
    {
        if ($value != null && $value != '') {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            } else {
                return asset('uploads/user/' . $value);
            }
        }
    }
    //Relation

    public function teacherProfile()
    {
        return $this->hasOne(TeacherProfile::class, 'user_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class, 'user_id');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    public function scopeAvailable($query)
    {
        if (auth('user')->check()) {
            $admin = auth('user')->user();
            if ($admin->hasRole('Super Admin')) {
            } elseif ($admin->hasAnyRole(['School Manager', 'Salaries Employee'])) {
                return $query->where('school_id', $admin->school_id);
            }
        }

    }

    //JWT

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
