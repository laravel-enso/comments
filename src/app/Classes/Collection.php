<?php

namespace LaravelEnso\CommentsManager\app\Classes;

use Illuminate\Contracts\Support\Responsable;
use LaravelEnso\CommentsManager\app\Models\Comment;

class Collection implements Responsable
{
    private $query = null;
    private $request;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function toResponse($request)
    {
        $this->query();

        return [
            'count' => $this->count(),
            'comments' => $this->collection(),
        ];
    }

    private function query()
    {
        $this->query = Comment::for($this->request)
            ->orderBy('created_at', 'desc');
    }

    private function count()
    {
        return $this->query->count();
    }

    private function collection()
    {
        return $this->query->skip($this->request['offset'])
            ->take($this->request['paginate'])
            ->get();
    }
}
