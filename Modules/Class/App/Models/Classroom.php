<?php

namespace Modules\Class\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Modules\Grade\App\Models\Grade;

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
    protected $fillable = ['title', 'grade_id', 'max_students', 'period_number'];
    protected $table = 'classes';
    protected $hidden = ['created_at', 'updated_at'];
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    //Relations
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    //Helper
    protected function scopeAvailable($query)
    {
        if (auth('admin')->check()) {
            $admin = auth('admin')->user();
            if ($admin->hasRole('Super Admin')) {
                // Show All Teachers
            } else if ($admin->hasRole('School Manager')) {
                // Show Teachers Related To Current School
                $query->where('school_id', $admin->school_id);
            }
        }
    }

}
