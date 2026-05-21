<?php

namespace LaravelEnso\Comments;

use Illuminate\Support\ServiceProvider;
use LaravelEnso\Mails\Preview\PreviewDefinition;
use LaravelEnso\Mails\Preview\PreviewRegistry;

class MailServiceProvider extends ServiceProvider
{
    public function boot(PreviewRegistry $registry): void
    {
        $registry->register(new PreviewDefinition(
            key: 'comment-tagged',
            name: 'Comment Tagged',
            view: 'laravel-enso/comments::emails.tagged',
            data: [
                'appellative' => 'Jane',
                'body' => 'Please review the latest note before the end of the day.',
                'url' => 'https://example.com/comments/1024',
            ],
            section: PreviewDefinition::Core,
        ));
    }
}
