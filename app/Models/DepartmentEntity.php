<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DepartmentEntity extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description'
    ];

    /**
     * Get all department accounts associated with this department
     */
    public function departmentAccounts(): HasMany
    {
        return $this->hasMany(DepartmentAccount::class);
    }
}