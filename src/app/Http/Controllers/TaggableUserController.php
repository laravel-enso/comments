<?php

namespace LaravelEnso\CommentsManager\app\Http\Controllers;

use App\Http\Controllers\Controller;
use LaravelEnso\CommentsManager\app\DAOs\Tags;

class TaggableUserController extends Controller
{
    private $tags;

    public function __construct(Tags $tags)
    {
        $this->tags = $tags;
    }

    public function __invoke($query = null)
    {
        return $this->tags->getTaggableUsers($query);
    }
}
