<?php

namespace Modules\Grade\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GradeCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name'];
    protected $hidden = ['created_at', 'updated_at'];


    //Relations
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
