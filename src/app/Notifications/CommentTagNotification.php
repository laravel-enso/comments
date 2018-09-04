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
            'title' => __('New Comment Tag'),
            'icon' => 'comment',
            'body' => __('You were just tagged').': '.$this->body,
        ]);
    }

    public function toMail($notifiable)
    {
        app()->setLocale($notifiable->lang());

        return (new MailMessage())
            ->subject(__(config('app.name')).': '.__('Comment Tag Notification'))
            ->markdown('laravel-enso/commentsmanager::emails.tagged', [
                'name' => $notifiable->first_name,
                'body' => $this->body,
                'url' => url($this->path),
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'body' => $this->body,
            'path' => $this->path,
            'icon' => 'comment',
        ];
    }
}
