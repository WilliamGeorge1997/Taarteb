<?php

namespace Modules\Student\App\Models;

use Spatie\Activitylog\LogOptions;
use Modules\Grade\App\Models\Grade;
use Modules\School\App\Models\School;
use Illuminate\Database\Eloquent\Model;
use Modules\Class\App\Models\Classroom;
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

    protected $fillable = ['name', 'email', 'identity_number', 'parent_email', 'grade_id', 'class_id', 'school_id', 'gender', 'is_active'];
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


    //Helper
    protected function scopeAvailable($query)
    {
        if (auth('user')->check()) {
            $admin = auth('user')->user();
            if ($admin->hasRole('Super Admin')) {
                // Show All Teachers
            } else if ($admin->hasRole('School Manager')) {
                // Show Teachers Related To Current School
                $query->where('school_id', $admin->school_id);
            }
        }
        // else if (auth('teacher')->check()) {
        //     $teacher = auth('teacher')->user();
        //     $query->where('school_id', $teacher->school_id);
        // }
    }
}

