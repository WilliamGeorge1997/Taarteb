<?php

namespace Modules\Class\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Classroom extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['title'];
    protected $table = 'classes';
    protected $hidden = ['created_at', 'updated_at'];

}
