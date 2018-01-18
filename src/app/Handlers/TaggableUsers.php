<?php

namespace LaravelEnso\CommentsManager\app\Handlers;

use LaravelEnso\Core\app\Models\User;

class TaggableUsers
{
    private $query;
    private $queryString;

    public function __construct(string $queryString = null)
    {
        $this->queryString = $queryString;

        $this->query = User::where('id', '<>', auth()->user()->id)
            ->limit(5);
    }

    public function get()
    {
        $this->query();

        return $this->query->get(['id', 'first_name', 'last_name']);
    }

    private function query()
    {
        collect(explode(' ', $this->queryString))
            ->each(function ($argument) {
                $this->query->where(function ($query) use ($argument) {
                    $query->where('first_name', 'like', '%'.$argument.'%')
                    ->orWhere('last_name', 'like', '%'.$argument.'%');
                });
            });
    }
}
