<?php

Route::middleware(['auth:api', 'api', 'core'])
	->prefix('api')
    ->namespace('LaravelEnso\CommentsManager\app\Http\Controllers')
    ->group(function () {
        Route::prefix('core')->as('core.')
            ->group(function () {
                Route::get('comments/getTaggableUsers/{query?}', 'TaggableUserController')
                    ->name('comments.getTaggableUsers');

                Route::resource('comments', 'CommentController', ['except' => ['show', 'edit', 'create']]);
            });
    });
