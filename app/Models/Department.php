<?php

namespace App\Models;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    //
    protected $guarded=[];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
