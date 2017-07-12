<?php

Route::middleware(['web', 'auth', 'core'])
    ->namespace('LaravelEnso\CommentsManager\app\Http\Controllers')
    ->group(function () {
        Route::prefix('core')->as('core.')
            ->group(function () {
                Route::get('comments/getTaggableUsers/{query?}', 'TaggableUserController@getList')
                    ->name('comments.getTaggableUsers');

                Route::resource('comments', 'CommentController', ['except' => ['show', 'edit', 'create']]);
            });
    });
