<?php

use Illuminate\Support\Facades\Route;
use LaravelEnso\Comments\Http\Controllers\Destroy;
use LaravelEnso\Comments\Http\Controllers\Index;
use LaravelEnso\Comments\Http\Controllers\Store;
use LaravelEnso\Comments\Http\Controllers\Update;
use LaravelEnso\Comments\Http\Controllers\Users;

Route::middleware(['api', 'auth', 'core'])
    ->prefix('api/core/comments')
    ->as('core.comments.')
    ->group(function () {
        Route::get('', Index::class)->name('index');
        Route::post('', Store::class)->name('store');
        Route::patch('{comment}', Update::class)->name('update');
        Route::delete('{comment}', Destroy::class)->name('destroy');

        Route::get('users', Users::class)->name('users');
    });
