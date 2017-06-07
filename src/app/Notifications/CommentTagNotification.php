<?php

namespace LaravelEnso\CommentsManager\app\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentTagNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $commentable;
    private $body;
    private $link;

    public function __construct($commentable, $body, $link)
    {
        $this->commentable = $commentable;
        $this->body = $body;
        $this->link = $link;
    }

    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->line(__('You were tagged in a message posted in') . ': ' . config('app.name'))
            ->line($this->body)
            ->line(__('To answer click the link below'))
            ->action(config('app.name'), $this->link)
            ->line(__('Thank you') . '!');
    }

    public function toArray($notifiable)
    {
        return [
            // customize the notification
            'body' => $this->body,
            'link' => $this->link,
        ];
    }
}
