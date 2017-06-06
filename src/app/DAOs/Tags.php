<?php

namespace LaravelEnso\CommentsManager\app\DAOs;

use LaravelEnso\CommentsManager\app\Models\Comment;

class Tags
{
    private $request;

    public function __construct(array $request = [])
    {
        $this->request = $request;
    }

    public function update(Comment $comment)
    {
        $comment->tagged_users()->sync($this->getTaggedUserIds());
    }

    private function getTaggedUserIds()
    {
        return array_column($this->request['tagged_users_list'], 'id');
    }

    public function getTaggableUsers($query)
    {
        $args = collect(explode(' ', $query));
        $userQuery = config('auth.providers.users.model')::limit(5);

        $args->each(function ($arg) use (&$userQuery) {
            $userQuery->where(function ($query) use ($arg) {
                $query->where('first_name', 'like', '%'.$arg.'%')
                    ->orWhere('last_name', 'like', '%'.$arg.'%');
            });
        });

        return $userQuery->get(['id', 'first_name', 'last_name']);
    }
}
