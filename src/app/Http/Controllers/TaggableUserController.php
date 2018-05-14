<?php

namespace LaravelEnso\CommentsManager\app\Http\Controllers;

use App\Http\Controllers\Controller;
use LaravelEnso\CommentsManager\app\Classes\TaggableUsers;

class TaggableUserController extends Controller
{
    public function __invoke($query = null)
    {
        return (new TaggableUsers($query));
    }
}
