<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    protected $task;
    
    /**
     * Create a new notification instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Task Assigned')
            ->line('You have been assigned a new task: ' . $this->task->name)
            ->action('View Task', url('/tasks/' . $this->task->id))
            ->line('Due date: ' . $this->task->due_date)
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'task_name' => $this->task->name,
            'priority' => $this->task->priority,
            'message' => 'You have been assigned a new task: ' . $this->task->name,
            'icon' => 'tasks',
            'url' => '/tasks/' . $this->task->id,
            'created_by' => $this->task->created_by,
            'created_by_name' => $this->task->creator ? $this->task->creator->first_name . ' ' . $this->task->creator->last_name : 'System',
        ];
    }
}