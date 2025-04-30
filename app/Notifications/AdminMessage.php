<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminMessage extends Notification implements ShouldQueue
{
    use Queueable;

    protected $title;
    protected $message;
    protected $link;
    
    /**
     * Create a new notification instance.
     *
     * @param string $title
     * @param string $message
     * @param string|null $link
     */
    public function __construct($title, $message, $link = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->link = $link;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject($this->title)
            ->line($this->message);
            
        if ($this->link) {
            $mail->action('View Details', url($this->link));
        }
        
        return $mail->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        // This method is used when storing the notification in the database
        return [
            'title' => $this->title,
            'message' => $this->message,
            'icon' => 'bell',
            'url' => $this->link ?? '/notifications',
        ];
    }
}