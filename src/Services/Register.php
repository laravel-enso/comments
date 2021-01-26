<?php

namespace LaravelEnso\Comments\Register;

use LaravelEnso\Comments\DynamicRelations\Commentable;
use LaravelEnso\Comments\Observers\Observer;
use LaravelEnso\DynamicMethods\Services\Methods;

class Register
{
    public static function handle(string $model)
    {
        Methods::bind($model, [Commentable::class]);
        $model::observe(Observer::class);
    }
}
