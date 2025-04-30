<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'description',
        'payment_date',
        'payment_method',
        'is_paid',
        'month',
        'year',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'is_paid' => 'boolean',
    ];

    /**
     * Get the user that owns the salary.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}