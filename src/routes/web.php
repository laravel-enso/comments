<?php

Route::group(['namespace' => 'LaravelEnso\CommentsManager\App\Http\Controllers', 'middleware' => ['web', 'auth', 'core']], function () {
    Route::group(['prefix' => 'core/comments', 'as' => 'core.comments.'], function () {
        Route::get('list', 'CommentsController@list')->name('list');
        Route::post('post', 'CommentsController@post')->name('post');
        Route::get('show/{comment}', 'CommentsController@show')->name('show');
        Route::delete('destroy/{comment}', 'CommentsController@destroy')->name('destroy');
        Route::patch('update/{comment}', 'CommentsController@update')->name('update');
        Route::get('getUsersList/{query?}', 'CommentsController@getUsersList')->name('getUsersList');
    });
});
