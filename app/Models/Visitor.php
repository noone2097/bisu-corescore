<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Visitor extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'signature',
    ];

    /**
     * Get the evaluation associated with the visitor.
     */
    public function evaluation(): HasOne
    {
        return $this->hasOne(Evaluation::class);
    }

    /**
     * Get the full name of the visitor.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
