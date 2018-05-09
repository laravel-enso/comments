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
        app()->setLocale($notifiable->preferences->global->lang);

        return (new MailMessage())
            ->view('laravel-enso/commentsmanager::emails.tagged',
                [
                    'intro' => __('You were tagged in a message posted in').': '.config('app.name'),
                    'messageBody' => $this->body,
                    'action' => __('To answer, click the button below.'),
                    'ending' => __('Thank you'),
                    'appName' => config('app.name'),
                    'appURL' => config('app.url').$this->link,
                ]);
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
