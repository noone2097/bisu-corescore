<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departments extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code'];
public function students()
{
    return $this->hasMany(Students::class);
}

public function courses()
{
    return $this->hasMany(Course::class, 'department_id');
}

public function faculty()
{
    return $this->hasMany(User::class, 'department_id')->where('role', 'faculty');
}

    
}
