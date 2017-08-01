<?php

namespace LaravelEnso\CommentsManager\app\DAOs;

use LaravelEnso\CommentsManager\app\Models\Comment;
use LaravelEnso\Core\app\Models\User;

class Tags
{
    private $request;

    public function __construct(array $request = [])
    {
        $this->request = $request;
    }

    public function update(Comment $comment)
    {
        $comment->taggedUsers()->sync($this->getTaggedUserIds());
    }

    private function getTaggedUserIds()
    {
        return array_column($this->request['taggedUserList'], 'id');
    }

    public function getTaggableUsers($query)
    {
        $userQuery = User::where('id', '<>', request()->user()->id)
            ->limit(5);

        collect(explode(' ', $query))->each(function ($argument) use (&$userQuery) {
            $userQuery->where(function ($query) use ($argument) {
                $query->where('first_name', 'like', '%'.$argument.'%')
                    ->orWhere('last_name', 'like', '%'.$argument.'%');
            });
        });

        return $userQuery->get(['id', 'first_name', 'last_name']);
    }
}
