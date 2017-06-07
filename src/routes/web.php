<?php

Route::group([
    'namespace'  => 'LaravelEnso\CommentsManager\app\Http\Controllers',
    'middleware' => ['web', 'auth', 'core'],
], function () {
    Route::group(['prefix' => 'core', 'as' => 'core.'], function () {
        Route::get('comments/getTaggableUsers/{query?}', 'TaggableUserController@getList')
        	->name('comments.getTaggableUsers');
        Route::resource('comments', 'CommentController');
    });
});
