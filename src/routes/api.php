<?php

Route::middleware(['web', 'auth', 'core'])
    ->prefix('api/core')->as('core.')
    ->namespace('LaravelEnso\CommentsManager\app\Http\Controllers')
    ->group(function () {
        Route::resource('comments', 'CommentController', ['except' => ['show', 'edit', 'create']]);
    });
