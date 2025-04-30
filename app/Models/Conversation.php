<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
    ];

    /**
     * Get the users in this conversation.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'conversation_user')
                    ->withTimestamps();
    }

    /**
     * Get the messages in this conversation.
     */
    public function messages()
    {
        return $this->belongsToMany(Message::class, 'conversation_message')
                    ->withTimestamps()
                    ->orderBy('created_at', 'asc');
    }

    /**
     * Get the latest message in this conversation.
     */
    public function latestMessage()
    {
        return $this->belongsToMany(Message::class, 'conversation_message')
                    ->orderBy('created_at', 'desc')
                    ->first();
    }

    /**
     * Get the unread messages count for a specific user.
     */
    public function unreadCount($userId)
    {
        return $this->belongsToMany(Message::class, 'conversation_message')
                    ->where('sender_id', '!=', $userId)
                    ->where('is_read', false)
                    ->count();
    }
}