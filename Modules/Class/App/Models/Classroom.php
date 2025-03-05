<?php

namespace Modules\Class\App\Models;

use Spatie\Activitylog\LogOptions;
use Modules\Grade\App\Models\Grade;
use Modules\School\App\Models\School;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classroom extends Model
{
    use HasFactory, LogsActivity;
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
    protected $fillable = ['name', 'grade_id', 'school_id', 'max_students', 'session_number'];
    protected $table = 'classes';
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    //Relations
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
        if (auth('user')->check()) {
            $admin = auth('user')->user();
            if ($admin->hasRole('Super Admin')) {
                // Show All Teachers
            } else if ($admin->hasRole('School Manager')) {
                // Show Teachers Related To Current School
                $query->where('school_id', $admin->school_id);
            }
        }
    }

}
