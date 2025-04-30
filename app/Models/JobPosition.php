<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'department_id',
        'min_salary',
        'max_salary',
        'description',
    ];

    /**
     * Get the department that owns the job position.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the users with this job position.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}