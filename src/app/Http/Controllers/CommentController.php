<?php

namespace LaravelEnso\CommentsManager\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use LaravelEnso\CommentsManager\app\Http\Requests\ValidateCommentRequest;
use LaravelEnso\CommentsManager\app\Http\Services\CommentService;
use LaravelEnso\CommentsManager\app\Models\Comment;

class CommentController extends Controller
{
    private $service;

    public function __construct(Request $request)
    {
        $this->service = new CommentService($request);
    }

    public function index()
    {
        return $this->service->index();
    }

    public function update(ValidateCommentRequest $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        return $this->service->update($comment);
    }

    public function store(ValidateCommentRequest $request, Comment $comment)
    {
        $this->authorize('create', $comment);

        return $this->service->store();
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('destroy', $comment);
        $this->service->destroy($comment);
    }
}
