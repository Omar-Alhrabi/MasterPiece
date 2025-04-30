<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'event_date',
        'event_time',
        'location',
        'organizer_id',
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'datetime',
    ];

    /**
     * Get the organizer of the event.
     */
    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }
    
    /**
     * Get formatted event date and time
     */
    public function getFormattedEventDateTimeAttribute()
    {
        $formattedDate = $this->event_date->format('M d, Y');
        $formattedTime = $this->event_time ? ' at ' . date('h:i A', strtotime($this->event_time)) : '';
        
        return $formattedDate . $formattedTime;
    }
}