<?php

namespace Modules\Subject\App\Models;

use Spatie\Activitylog\LogOptions;
use Modules\Grade\App\Models\Grade;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name' , 'grade_id'];

    protected $hidden = ['created_at', 'updated_at'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('Subject')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    //Relations
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
}
