<?php

namespace Modules\School\App\Models;

use Modules\Admin\App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class School extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name','address','phone','email','is_active'];

    //Relations
    public function manager()
    {
        return $this->hasOne(Admin::class);
    }
}