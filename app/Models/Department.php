<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'manager_id',
        'description',
    ];

    /**
     * Get the manager of the department.
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Get the users (employees) in this department.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the job positions in this department.
     */
    public function jobPositions()
    {
        return $this->hasMany(JobPosition::class);
    }
}