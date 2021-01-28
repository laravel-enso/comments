<?php

namespace LaravelEnso\Comments\Services;

use LaravelEnso\Comments\DynamicRelations\Commentable as Relation;
use LaravelEnso\Comments\Observers\Observer;
use LaravelEnso\DynamicMethods\Services\Methods;

class Commentable
{
    public static function register(string $model)
    {
        Methods::bind($model, [Relation::class]);
        $model::observe(Observer::class);
    }
}
