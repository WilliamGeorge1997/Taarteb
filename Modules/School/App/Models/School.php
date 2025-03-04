<?php

namespace Modules\School\App\Models;

use Modules\User\App\Models\User;
use Spatie\Activitylog\LogOptions;
use Modules\Grade\App\Models\Grade;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class School extends Model
{
    use HasFactory, LogsActivity;

      /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name', 'is_active'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('School')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }


    //Relations
    public function manager()
    {
        return $this->hasOne(User::class);
    }

    public function grades()
    {
        return $this->belongsToMany(Grade::class, 'school_grades');
    }
}
