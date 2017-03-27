<?php

namespace LaravelEnso\CommentsManager\app\Http\Controllers;

use App\Http\Controllers\Controller;
use LaravelEnso\CommentsManager\app\Models\Comment;
use LaravelEnso\CommentsManager\app\Notifications\CommentTagNotification;

class CommentsController extends Controller
{
    public function post()
    {
        $comment = new Comment();
        $class = request()->type;
        $commentable = $class::find(request()->id);

        \DB::transaction(function () use ($commentable, $comment) {
            $comment->body = request()->comment;
            $comment->user_id = request()->user()->id;
            $commentable->comments()->save($comment);
            $this->applyTags($comment, $commentable);
        });

        return [

            'comment' => $comment->fresh()->load(['user', 'tagged_users']),
            'count'   => $commentable->comments()->count(),
        ];
    }

    public function update(Comment $comment)
    {
        $comment->body = request()->comment;
        $comment->is_edited = 1;
        $comment->save();
        $usersList = array_column(request()->taggedUsers, 'id');
        $comment->tagged_users()->sync($usersList);
        $this->applyTags($comment);

        return $comment->load(['user', 'tagged_users']);
    }

    public function applyTags($comment, $commentable = null)
    {
        $commentable = $commentable ?: $comment->commentable_type::find($comment->commentable_id);
        $userClass = config('auth.providers.users.model');
        foreach (request()->taggedUsers as $taggedUser) {
            $user = $userClass::find($taggedUser['id']);

            if (!$user->comments_tags->contains($comment)) {
                $user->comments_tags()->save($comment);
            }

            $user->notify(new CommentTagNotification($commentable, request()->type, $comment->body, '#'));
        }
    }

    public function show(Comment $comment)
    {
        return $comment->load(['user']);
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
    }

    public function list()
    {
        $class = request('type');
        $commentable = $class::find(request('id'));
        $list = $commentable->comments()->orderBy('id', 'desc')->with('user')->with('tagged_users')->skip(request('offset'))->take(request('paginate'))->get();
        $count = $commentable->comments()->count();

        return [

            'list'  => $list,
            'count' => $count,
        ];
    }

    public function getUsersList($query = null)
    {
        $query = null;
        $userClass = config('auth.providers.users.model');
        $usersList = $userClass::where('first_name', 'like', '%'.$query.'%')->orWhere('last_name', 'like', '%'.$query.'%')->limit(5)->get();

        $response = [];

        foreach ($usersList as $user) {
            $response[] = [

                'id'     => $user->id,
                'avatar' => $user->avatar_link,
                'name'   => $user->full_name,
            ];
        }

        return ['usersList' => $response];
    }
}
