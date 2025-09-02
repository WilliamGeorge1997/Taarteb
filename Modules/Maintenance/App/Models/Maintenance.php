<?php

namespace Modules\Maintenance\App\Models;

use Spatie\Activitylog\LogOptions;
use Modules\School\App\Models\School;
use Illuminate\Database\Eloquent\Model;
use Modules\Employee\App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Maintenance extends Model
{
    use HasFactory;
    const STATUS_PENDING = "pending";
    const STATUS_ACCEPTED = "accepted";
    const STATUS_REJECTED = "rejected";
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['description', 'employee_id', 'school_id', 'image', 'date', 'price', 'reject_reason', 'status'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('Maintenance')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }
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
                return asset('uploads/maintenance/' . $value);
            }
        }
    }
    protected function scopeAvailable($query)
    {
        if (auth('user')->check()) {
            $admin = auth('user')->user();
            if ($admin->hasRole('Super Admin')) {
            } else if ($admin->hasRole('School Manager')) {
                $query->where('school_id', $admin->school_id);
            }
        }
    }
    //Relations
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
