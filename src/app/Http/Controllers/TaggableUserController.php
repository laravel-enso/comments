<?php

namespace LaravelEnso\CommentsManager\app\Http\Controllers;

use App\Http\Controllers\Controller;
use LaravelEnso\Core\app\Models\User;

class TaggableUserController extends Controller
{
    public function __invoke($query = null)
    {
        $userQuery = User::where('id', '<>', request()->user()->id)
            ->limit(5);

        collect(explode(' ', $query))->each(function ($argument) use ($userQuery) {
            $userQuery->where(function ($query) use ($argument) {
                $query->where('first_name', 'like', '%'.$argument.'%')
                    ->orWhere('last_name', 'like', '%'.$argument.'%');
            });
        });

        return $userQuery->get(['id', 'first_name', 'last_name']);
    }
}
