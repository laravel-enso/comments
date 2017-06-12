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
        $arguments = collect(explode(' ', $query));
        $userQuery = config('auth.providers.users.model')::where('id', '<>', request()->user()->id)
            ->limit(5);

        $arguments->each(function ($argument) use (&$userQuery) {
            $userQuery->where(function ($query) use ($argument) {
                $query->where('first_name', 'like', '%'.$argument.'%')
                    ->orWhere('last_name', 'like', '%'.$argument.'%');
            });
        });

        return $userQuery->get(['id', 'first_name', 'last_name']);
    }
}
