<?php

namespace App\Notifications;

use App\Models\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class LeaveRequestSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $leave;
    
    /**
     * Create a new notification instance.
     */
    public function __construct(Leave $leave)
    {
        $this->leave = $leave;
        
        // Log constructor call to debug notification creation
        Log::info('LeaveRequestSubmitted notification created', [
            'leave_id' => $leave->id,
            'user_id' => $leave->user_id
        ]);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // Only use database channel for now to avoid mail configuration issues
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Leave Request Submitted')
            ->line('A new leave request has been submitted by ' . $this->leave->user->first_name . ' ' . $this->leave->user->last_name)
            ->action('Review Request', url('/leaves/approve'))
            ->line('From: ' . $this->leave->start_date . ' To: ' . $this->leave->end_date)
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        try {
            // Log notification database serialization for debugging
            Log::info('LeaveRequestSubmitted toArray called', [
                'notifiable_id' => $notifiable->id,
                'leave_id' => $this->leave->id
            ]);
            
            // Make sure we have all data available and use null coalescing to avoid errors
            $userName = $this->leave->user ? 
                ($this->leave->user->first_name . ' ' . $this->leave->user->last_name) : 
                'Unknown User';
                
            $leaveTypeName = $this->leave->leaveType ? $this->leave->leaveType->name : 'Leave';
            
            return [
                'leave_id' => $this->leave->id,
                'user_id' => $this->leave->user_id,
                'user_name' => $userName,
                'leave_type' => $leaveTypeName,
                'start_date' => $this->leave->start_date,
                'end_date' => $this->leave->end_date,
                'total_days' => $this->leave->total_days,
                'message' => 'New leave request submitted by ' . $userName,
                'icon' => 'calendar-alt',
                'url' => '/leaves/approve',
            ];
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error in LeaveRequestSubmitted toArray', [
                'error' => $e->getMessage(),
                'leave_id' => $this->leave->id ?? 'unknown'
            ]);
            
            // Return a minimal array that won't cause further errors
            return [
                'message' => 'New leave request submitted',
                'icon' => 'calendar-alt',
                'url' => '/leaves/approve',
            ];
        }
    }
}