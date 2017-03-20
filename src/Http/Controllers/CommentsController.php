<?php

namespace LaravelEnso\CommentsManager\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use LaravelEnso\CommentsManager\Comment;
use LaravelEnso\CommentsManager\Notifications\CommentTagNotification;

class CommentsController extends Controller
{

    public function post()
    {
        $comment     = new Comment;
        $class       = request()->type;
        $commentable = $class::find(request()->id);

        \DB::transaction(function () use ($commentable, $comment) {

            $comment->body    = request()->comment;
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
        $comment->body      = request()->comment;
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

        foreach (request()->taggedUsers as $taggedUser) {

            $user = User::find($taggedUser['id']);

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
        $id = $comment->id;
        $comment->delete();
    }

    function list() {
        $class       = request('type');
        $commentable = $class::find(request('id'));
        $list        = $commentable->comments()->orderBy('id', 'desc')->with('user')->with('tagged_users')->skip(request('offset'))->take(request('paginate'))->get();
        $count       = $commentable->comments()->count();

        return [

            'list'  => $list,
            'count' => $count,
        ];
    }

    public function getUsersList($query = null)
    {
        $query     = null;
        $usersList = User::where('first_name', 'like', '%' . $query . '%')->orWhere('last_name', 'like', '%' . $query . '%')->limit(5)->get();

        $response = [];

        foreach ($usersList as $user) {

            $response[] = [

                'id'     => $user->id,
                'avatar' => $user->avatar_saved_name,
                'name'   => $user->full_name,
            ];
        }

        return ['usersList' => $response];
    }
}
