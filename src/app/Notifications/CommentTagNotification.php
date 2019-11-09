<?php

namespace LaravelEnso\Comments\app\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

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

    public function via()
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toBroadcast()
    {
        return new BroadcastMessage([
            'level' => 'info',
            'title' => __('New Comment Tag'),
            'icon' => 'comment',
            'body' => __('You were just tagged').': '.$this->body,
        ]);
    }

    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject(__(config('app.name')).': '.__('Comment Tag Notification'))
            ->markdown('laravel-enso/comments::emails.tagged', [
                'appellative' => $notifiable->person->appellative
                    ?? $notifiable->person->name,
                'body' => $this->body,
                'url' => url($this->path),
            ]);
    }

    public function toArray()
    {
        return [
            'body' => $this->body,
            'path' => $this->path,
            'icon' => 'comment',
        ];
    }
}
