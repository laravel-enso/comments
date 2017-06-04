<?php

namespace LaravelEnso\CommentsManager\app\DAOs;

use Illuminate\Http\Request;
use LaravelEnso\CommentsManager\app\Models\Comment;

class Tags
{
	private $request;

	public function __construct(array $request)
	{
		$this->request = $request;
	}

	public function update(Comment $comment)
	{
    	$taggedUserIds = $this->getTaggedUserIds();
		$comment->tagged_users()->sync($taggedUserIds);
	}

	private function getTaggedUserIds()
	{
		return array_column($this->request['tagged_users_list'], 'id');
	}

    public static function getTaggableUsers($query)
    {
    	$args = collect(explode(' ', $query));
        $users = config('auth.providers.users.model')::limit(5);

        $args->each(function($arg) use (&$users) {
        	$users->where('first_name', 'like', '%' . $arg . '%')
        		->orWhere('last_name', 'like', '%' . $arg . '%');
        });

        return $users->get(['id', 'first_name', 'last_name']);
    }
}