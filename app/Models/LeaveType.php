<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'days_allowed',
        'is_paid',
    ];

    /**
     * Get the leaves for the leave type.
     */
    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }
}