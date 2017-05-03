<?php

namespace LaravelEnso\CommentsManager\app\Http\Controllers;

use App\Http\Controllers\Controller;
use LaravelEnso\CommentsManager\app\Models\Comment;
use LaravelEnso\CommentsManager\app\Notifications\CommentTagNotification;

class CommentsController extends Controller
{
    public function post()
    {
        $comment = new Comment([
            'body'    => request('comment'),
            'user_id' => request()->user()->id,
        ]);
        $commentable = request('type')::find(request('id'));

        \DB::transaction(function () use ($comment, $commentable) {
            $commentable->comments()->save($comment);
            $usersList = array_column(request('tagged_users_list'), 'id');
            $comment->tagged_users()->sync($usersList);
            $this->applyTags($comment);
        });

        return [
            'comment' => $comment->fresh(),
            'count'   => $commentable->comments()->count(),
        ];
    }

    public function update(Comment $comment)
    {
        $this->authorize('update', $comment);
        $comment->fill(request('comment'));

        \DB::transaction(function () use ($comment) {
            $comment->save();
            $usersList = array_column(request('comment')['tagged_users_list'], 'id');
            $comment->tagged_users()->sync($usersList);
            $this->applyTags($comment);
        });

        return $comment;
    }

    public function applyTags($comment)
    {
        $commentable = $comment->commentable_type::find($comment->commentable_id);

        foreach ($comment->tagged_users as $taggedUser) {
            $taggedUser->notify(class_exists(\App\Notifications\CommentTagNotification::class) ?
                new \App\Notifications\CommentTagNotification($commentable, request('type'), $comment->body, request('url')) :
                new CommentTagNotification($commentable, request('type'), $comment->body, request('url'))
            );
        }
    }

    public function show(Comment $comment)
    {
        return $comment->load(['user']);
    }

    public function destroy(Comment $comment)
    {
        if (!$comment->is_editable) {
            throw new \EnsoException('You are not allowed to do this action');
        }

        $comment->delete();
    }

    public function list()
    {
        $commentable = request('type')::find(request('id'));
        $list = $commentable->comments()->orderBy('id', 'desc')
            ->skip(request('offset'))
            ->take(request('paginate'))
            ->get();
        $count = $commentable->comments()->count();

        return [

            'list'  => $list,
            'count' => $count,
        ];
    }

    public function getUsersList($query = null)
    {
        $usersList = config('auth.providers.users.model')::where('first_name', 'like', '%'.$query.'%')
            ->orWhere('last_name', 'like', '%'.$query.'%')
            ->limit(5)
            ->get();

        $response = [];

        foreach ($usersList as $user) {
            $response[] = [
                'id'          => $user->id,
                'name'        => $user->full_name,
                'avatar_link' => $user->avatar_link,
            ];
        }

        return ['usersList' => $response];
    }
}
