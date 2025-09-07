<?php

namespace Modules\Salary\App\Models;

use Modules\School\App\Models\School;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Modules\Employee\App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Salary extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['created_by', 'employee_id', 'school_id', 'salary', 'month', 'year'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('Salary')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }


    public function createdBy()
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }


    protected function scopeAvailable($query)
    {
        if (auth('user')->check()) {
            $admin = auth('user')->user();
            if ($admin->hasRole('Super Admin')) {
            } else if ($admin->hasRole('School Manager') || $admin->hasRole('Financial Director')) {
                $query->where('school_id', $admin->school_id);
            }
        }
    }
}
