<?php

namespace LaravelEnso\CommentsManager\app\Http\Controllers;

use App\Http\Controllers\Controller;
use LaravelEnso\CommentsManager\app\Http\Responses\TaggableUsers;

class TaggableUserController extends Controller
{
    public function __invoke()
    {
        return new TaggableUsers();
    }
}
