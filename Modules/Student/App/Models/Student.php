<?php

namespace Modules\Student\App\Models;

use Spatie\Activitylog\LogOptions;
use Modules\Grade\App\Models\Grade;
use Modules\School\App\Models\School;
use Illuminate\Database\Eloquent\Model;
use Modules\Class\App\Models\Classroom;
use Modules\Session\App\Models\Session;
use Modules\Session\App\Models\Attendance;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory, LogsActivity;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('Student')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    /**
     * The attributes that are mass assignable.
     */

    protected $fillable = ['name', 'email', 'identity_number', 'parent_email', 'grade_id', 'class_id', 'school_id', 'gender', 'is_graduated', 'is_active'];
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    //Relations
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function class()
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    //Helper
    protected function scopeAvailable($query)
    {
        if (auth('user')->check()) {
            $admin = auth('user')->user();
            if ($admin->hasRole('Super Admin')) {
                // Show All Students
            } else if ($admin->hasRole('School Manager')) {
                // Show Students Related To Current School
                $query->where('school_id', $admin->school_id);
            } else if ($admin->hasRole('Teacher')) {
                $query->whereHas('attendance', function ($query) use ($admin) {
                    $query->whereHas('session', function ($query) use ($admin) {
                        $query->where('teacher_id', $admin->teacherProfile->id);
                    });
                });
            }
        }
    }
}

