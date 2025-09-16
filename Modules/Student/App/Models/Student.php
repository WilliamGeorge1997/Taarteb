<?php

namespace Modules\Student\App\Models;

use Modules\User\App\Models\User;
use Spatie\Activitylog\LogOptions;
use Modules\Grade\App\Models\Grade;
use Modules\School\App\Models\School;
use Illuminate\Database\Eloquent\Model;
use Modules\Class\App\Models\Classroom;
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

    protected $fillable = ['name', 'email', 'identity_number', 'parent_email', 'parent_phone', 'grade_id', 'class_id', 'school_id', 'gender', 'is_graduated', 'is_active', 'user_id', 'is_fee_paid', 'application_form'];
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function getApplicationFormAttribute($value)
    {
        if ($value != null && $value != '') {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            } else {
                return asset('uploads/student/application_form/' . $value);
            }
        }
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

    public function user()
    {
        return $this->belongsTo(User::class);
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
    protected function scopeAvailableAll($query)
    {
        if (auth('user')->check()) {
            $admin = auth('user')->user();
            if ($admin->hasRole('Super Admin')) {
                // Show All Students
            } else if ($admin->hasRole('School Manager')) {
                // Show Students Related To Current School
                $query->where('school_id', $admin->school_id);
            } else if ($admin->hasRole('Teacher')) {
                $query->where('school_id', $admin->school_id);
            }
        }
    }

    public function canLogin()
    {
        // Student can login if they are registered and have paid fees
        if ($this->is_register) {
            // If registered, application form must not be null and fee must be paid
            if (!is_null($this->application_form) && $this->is_fee_paid) {
                return ['status' => true, 'message' => 'Student can login successfully'];
            } else if (is_null($this->application_form) && !$this->is_fee_paid) {
                return ['status' => false, 'message' => 'Application form is required and fees must be paid'];
            } else if (is_null($this->application_form)) {
                return ['status' => false, 'message' => 'Application form is required'];
            } else if (!$this->is_fee_paid) {
                return ['status' => false, 'message' => 'Fees must be paid'];
            }
        } else {
            // If not registered, only fee payment is required (application form can be null)
            if ($this->is_fee_paid) {
                return ['status' => true, 'message' => 'Student can login successfully'];
            } else {
                return ['status' => false, 'message' => 'Fees must be paid'];
            }
        }
    }
}

