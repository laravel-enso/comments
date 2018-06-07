<?php

namespace LaravelEnso\CommentsManager\app\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use LaravelEnso\CommentsManager\app\Models\Comment;

class CommentsIndex implements Responsable
{
    private $query = null;
    private $request;

    public function toResponse($request)
    {
        $this->request($request)
            ->query();

        return [
            'count' => $this->count(),
            'comments' => $this->collection(),
        ];
    }

    private function query()
    {
        $this->query = Comment
            ::for($this->request->only(['commentable_id', 'commentable_type']))
            ->orderBy('created_at', 'desc');
    }

    private function count()
    {
        return $this->query->count();
    }

    private function collection()
    {
        return $this->query
            ->skip($this->request->get('offset'))
            ->take($this->request->get('paginate'))
            ->get();
    }

    private function request($request)
    {
        $this->request = $request;

        return $this;
    }
}
