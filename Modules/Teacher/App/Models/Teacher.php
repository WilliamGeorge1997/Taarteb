<?php

namespace Modules\Teacher\App\Models;

use Spatie\Activitylog\LogOptions;
use Modules\Grade\App\Models\Grade;
use Modules\School\App\Models\School;
use Spatie\Permission\Traits\HasRoles;
use Modules\Subject\App\Models\Subject;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Teacher extends Authenticatable implements JWTSubject
{
    use HasFactory, LogsActivity, HasRoles;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('Teacher')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name', 'email', 'phone', 'password', 'image', 'subject_id', 'grade_id', 'school_id', 'is_active'];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function getImageAttribute($value)
    {
        return !is_null($value) && $value !== '' && filter_var($value, FILTER_VALIDATE_URL) ? $value : asset('uploads/teacher/' . $value);
    }

    //Relations
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }


    //Helper
    protected function scopeAvailable($query)
    {
        if(auth('admin')->check()){
            $admin = auth('admin')->user();
            if($admin->hasRole('Super Admin')){
                // Show All Teachers
            }else if($admin->hasRole('School Manager')){
                // Show Teachers Related To Current School
                $query->where('school_id',$admin->school_id);
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
