<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name', 
        'email',
        'password',
        'role',
        'phone_number',
        'date_of_birth',
        'gender',
        'job_position_id',
        'department_id',
        'manager_id',
        'hire_date',
        'salary',
        'employment_status',
        'termination_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_of_birth' => 'date',
        'hire_date' => 'date',
        'termination_date' => 'date',
    ];
    
    /**
     * Get the job position that owns the user.
     */
    public function jobPosition()
    {
        return $this->belongsTo(JobPosition::class);
    }
    
    /**
     * Get the department that owns the user.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    
    /**
     * Get the manager of the user.
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
    
    /**
     * Get the subordinates of the user.
     */
    public function subordinates()
    {
        return $this->hasMany(User::class, 'manager_id');
    }
    
    /**
     * Get the user's projects.
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'user_projects')
                    ->withPivot('role', 'assigned_date', 'end_date')
                    ->withTimestamps();
    }
    
    /**
     * Get the tasks assigned to the user.
     */
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }
    
    /**
     * Get the tasks created by the user.
     */
    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'created_by');
    }
    
    /**
     * Get the user's attendance records.
     */
    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }
    
    /**
     * Get the user's leave requests.
     */
    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }
    
    /**
     * Get the leaves approved by the user.
     */
    public function approvedLeaves()
    {
        return $this->hasMany(Leave::class, 'approved_by');
    }
    
    /**
     * Get the user's salary records.
     */
    public function salaries()
    {
        return $this->hasMany(Salary::class);
    }
    public function tasks()
    {
        return $this->assignedTasks()->orWhere('created_by', $this->id);
    }
    /**
     * Check if user is admin.
     */
    public function isAdmin()
    {
        return in_array($this->role, ['admin', 'superadmin']);
        
    }
    public function sentMessages()
{
    return $this->hasMany(Message::class, 'sender_id');
}

/**
 * Get all messages received by this user.
 */
public function receivedMessages()
{
    return $this->hasMany(Message::class, 'receiver_id');
}

/**
 * Get all conversations this user is part of.
 */
public function conversations()
{
    return $this->belongsToMany(Conversation::class, 'conversation_user')
                ->withTimestamps();
}

/**
 * Get unread messages count.
 */
    /**
     * Get count of unread messages for this user
     */
    public function unreadMessagesCount()
    {
        return Message::whereHas('conversations', function ($query) {
            $query->whereHas('users', function ($q) {
                $q->where('users.id', $this->id);
            });
        })
            ->where('sender_id', '!=', $this->id)
            ->where('is_read', false)
            ->count();
    }

/**
 * Get conversations with unread messages.
 */
public function conversationsWithUnreadMessages()
{
    return $this->conversations()
                ->withCount(['messages as unread_count' => function ($query) {
                    $query->where('sender_id', '!=', $this->id)
                          ->where('is_read', false);
                }])
                ->having('unread_count', '>', 0);
}

public function isOnline() { 

    return true; 
    
   }
}