<?php

namespace LaravelEnso\CommentsManager\app\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

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

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'level' => 'info',
            'body'  => __('You were just tagged') . ': ' . $this->body,
        ]);
    }

    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->view('emails.tagged',
                [
                    'intro'       => __('You were tagged in a message posted in') . ': ' . config('app.name'),
                    'messageBody' => $this->body,
                    'action'      => __('To answer, click the button below.'),
                    'ending'      => __('Thank you'),
                    'appName'     => config('app.name'),
                    'appURL'      => config('app.url') . $this->path,
                ]);
    }

    public function toArray($notifiable)
    {
        return [
            'body' => $this->body,
            'path' => $this->path,
        ];
    }
}
