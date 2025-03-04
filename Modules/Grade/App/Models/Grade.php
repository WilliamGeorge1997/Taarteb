<?php

namespace Modules\Grade\App\Models;

use Modules\School\App\Models\School;
use Illuminate\Database\Eloquent\Model;
use Modules\Subject\App\Models\Subject;
use Modules\Grade\App\Models\GradeCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grade extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name', 'grade_category_id'];

    protected $hidden = ['created_at', 'updated_at'];

    //Relation

    public function gradeCategory()
    {
        return $this->belongsTo(GradeCategory::class);
    }

    public function schools()
    {
        return $this->belongsToMany(School::class, 'school_grades');
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'grade_id', 'id');
    }
}
