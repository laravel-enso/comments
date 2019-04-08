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
        app()->setLocale($notifiable->preferences->global->lang);

        return (new MailMessage())
            ->line('You were tagged in a message posted in'.': '.config('app.name'))
            ->line('Message: '.$this->body)
            ->line('To answer, click the button below.')
            ->action(config('app.name'), $this->link);
    }

    public function toArray($notifiable)
    {
        return [
            // customize the push / database notification
            'body' => $this->body,
            'link' => $this->link,
        ];
    }
}
