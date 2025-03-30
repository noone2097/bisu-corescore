<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Department extends Model
{
    use Notifiable;

    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    public function students()
    {
        return $this->hasMany(Students::class);
    }

    public function faculty()
    {
        return $this->hasMany(User::class)->where('role', 'faculty');
    }
}