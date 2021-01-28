<?php

namespace LaravelEnso\Comments;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use LaravelEnso\Comments\DynamicRelations\Commentable as Relation;
use LaravelEnso\Comments\Observers\Observer;
use LaravelEnso\DynamicMethods\Services\Methods;

class CommentableServiceProvider extends ServiceProvider
{
    protected array $register = [];

    public function boot()
    {
        Collection::wrap($this->register)
            ->each(function ($model) {
                Methods::bind($model, [Relation::class]);
                $model::observe(Observer::class);
            });
    }
}
