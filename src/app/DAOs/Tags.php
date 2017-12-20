<?php

namespace LaravelEnso\CommentsManager\app\DAOs;

use LaravelEnso\Core\app\Models\User;
use LaravelEnso\CommentsManager\app\Models\Comment;

class Tags
{
    public function update(array $request, Comment $comment)
    {
        $comment->taggedUsers()->sync($this->getTaggedUserIds($request));
    }

    private function getTaggedUserIds(array $request)
    {
        return array_column($request['taggedUserList'], 'id');
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
