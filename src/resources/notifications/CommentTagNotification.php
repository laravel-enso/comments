<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentTagNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $commentable;
    private $body;
    private $link;

    public function __construct($commentable, $body, $link)
    {
        $this->commentable = $commentable;
        $this->body = $body;
        $this->link = $link;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->line('Ai fost tagguit intr-un mesaj postat in aplicatia '.config('app.name'))
            ->line($this->body)
            ->line('Pentru a raspunde acceseaza linkul de mai jos')
            ->action(config('app.name'), $this->link)
            ->line('Iti multumim!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            // customize the notification
            'body' => $this->body,
            'link' => $this->link,
        ];
    }
}
