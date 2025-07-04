<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProjectDeadlineApproaching extends Notification implements ShouldQueue
{
    use Queueable;

    protected $project;
    protected $daysRemaining;
    
    /**
     * Create a new notification instance.
     */
    public function __construct(Project $project, int $daysRemaining)
    {
        $this->project = $project;
        $this->daysRemaining = $daysRemaining;
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
            ->subject('Project Deadline Approaching')
            ->line('The project "' . $this->project->name . '" is due in ' . $this->daysRemaining . ' days.')
            ->action('View Project', url('/projects/' . $this->project->id))
            ->line('End date: ' . $this->project->end_date)
            ->line('Thank you for your attention to this matter.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'project_id' => $this->project->id,
            'project_name' => $this->project->name,
            'days_remaining' => $this->daysRemaining,
            'message' => 'Project "' . $this->project->name . '" is due in ' . $this->daysRemaining . ' days',
            'icon' => 'calendar-check',
            'url' => '/projects/' . $this->project->id,
        ];
    }
}