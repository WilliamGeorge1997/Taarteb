<?php

namespace Modules\Teacher\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Teacher\Database\factories\TeacherFactory;

class Teacher extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): TeacherFactory
    {
        //return TeacherFactory::new();
    }
}
