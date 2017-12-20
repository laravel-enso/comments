<?php

namespace LaravelEnso\CommentsManager\app\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CommentTagNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $commentable;
    private $body;
    private $path;

    public function __construct($commentable, $body, $path)
    {
        $this->commentable = $commentable;
        $this->body = $body;
        $this->path = $path;
    }

    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->line(__('You were tagged in a message posted in').': '.config('app.name'))
            ->line($this->body)
            ->line(__('To answer click the link below'))
            ->action(config('app.name'), $this->path)
            ->line(__('Thank you').'!');
    }

    public function toArray($notifiable)
    {
        return [
            // customize the push / database notification
            'body' => $this->body,
            'path' => $this->path,
        ];
    }
}
