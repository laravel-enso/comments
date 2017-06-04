<?php

namespace LaravelEnso\CommentsManager\app\Http\Controllers;

use App\Http\Controllers\Controller;
use LaravelEnso\CommentsManager\app\DAOs\Tags;

class TaggableUserController extends Controller
{
    public function getList($query = null)
    {
        return Tags::getTaggableUsers($query);
    }
}
