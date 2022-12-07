<?php

namespace LaravelEnso\Comments\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;

class CommentTagNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private string $body,
        private string $path
    ) {
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
            'icon'  => 'comment',
            'body'  => __('You were just tagged').': '.$this->body,
        ]);
    }

    public function toMail($notifiable)
    {
        $app = Config::get('app.name');

        return (new MailMessage())
            ->subject("[ {$app} ] {$this->subject()}")
            ->markdown('laravel-enso/comments::emails.tagged', [
                'appellative' => $notifiable->person->appellative(),
                'body'        => $this->body,
                'url'         => url($this->path),
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

    private function subject(): string
    {
        return __('Comment Tag Notification');
    }
}
