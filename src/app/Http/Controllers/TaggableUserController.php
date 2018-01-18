<?php

namespace LaravelEnso\CommentsManager\app\Http\Controllers;

use App\Http\Controllers\Controller;
use LaravelEnso\CommentsManager\app\Handlers\TaggableUsers;

class TaggableUserController extends Controller
{
    public function __invoke($query = null)
    {
        return (new TaggableUsers($query))->get();
    }
}
