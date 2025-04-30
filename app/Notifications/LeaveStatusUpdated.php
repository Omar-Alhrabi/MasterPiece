<?php

namespace App\Notifications;

use App\Models\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class LeaveStatusUpdated extends Notification implements ShouldQueue
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
        Log::info('LeaveStatusUpdated notification created', [
            'leave_id' => $leave->id,
            'status' => $leave->status
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
        $statusMessages = [
            'Approved' => 'Your leave request has been approved',
            'Rejected' => 'Your leave request has been rejected',
            'Cancelled' => 'Your leave request has been cancelled',
        ];

        $message = $statusMessages[$this->leave->status] ?? 'Your leave request status has been updated';

        return (new MailMessage)
            ->subject('Leave Request ' . $this->leave->status)
            ->line($message)
            ->line('From: ' . $this->leave->start_date . ' To: ' . $this->leave->end_date)
            ->action('View Details', url('/leaves'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        try {
            // Log notification database serialization for debugging
            Log::info('LeaveStatusUpdated toArray called', [
                'notifiable_id' => $notifiable->id,
                'leave_id' => $this->leave->id,
                'status' => $this->leave->status
            ]);
            
            $statusMessages = [
                'Approved' => 'Your leave request has been approved',
                'Rejected' => 'Your leave request has been rejected',
                'Cancelled' => 'Your leave request has been cancelled',
            ];
    
            $message = $statusMessages[$this->leave->status] ?? 'Your leave request status has been updated';
            $icon = $this->leave->status == 'Approved' ? 'check-circle' : 
                    ($this->leave->status == 'Rejected' ? 'times-circle' : 'info-circle');
    
            return [
                'leave_id' => $this->leave->id,
                'status' => $this->leave->status,
                'start_date' => $this->leave->start_date ?? null,
                'end_date' => $this->leave->end_date ?? null,
                'message' => $message,
                'icon' => $icon,
                'url' => '/leaves',
            ];
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error in LeaveStatusUpdated toArray', [
                'error' => $e->getMessage(),
                'leave_id' => $this->leave->id ?? 'unknown'
            ]);
            
            // Return a minimal array that won't cause further errors
            return [
                'message' => 'Your leave request status has been updated',
                'icon' => 'info-circle',
                'url' => '/leaves',
            ];
        }
    }
}