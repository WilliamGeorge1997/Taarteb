<?php

namespace Modules\Common\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Common\Database\factories\HistoryFactory;

class History extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['day', 'session_number', 'semester', 'year', 'teacher_id', 'student_id', 'subject_id', 'session_id', 'class_id', 'school_id', 'is_present'];

}
