<?php

namespace LaravelEnso\CommentsManager\app\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class TaggableUsers implements Responsable
{
    private $query = null;
    private $queryString;

    public function toResponse($request)
    {
        $this->queryString = $request->get('query');

        return $this->query()
            ->filter()
            ->get();
    }

    private function get()
    {
        return $this->query
            ->get(['id', 'first_name', 'last_name']);
    }

    private function query()
    {
        $this->query = config('auth.providers.users.model')
            ::where('id', '<>', auth()->user()->id)
            ->limit(5);

        return $this;
    }

    private function filter()
    {
        collect(explode(' ', $this->queryString))
            ->each(function ($argument) {
                $this->query->where(function ($query) use ($argument) {
                    $query->where('first_name', 'like', '%'.$argument.'%')
                        ->orWhere('last_name', 'like', '%'.$argument.'%');
                });
            });

        return $this;
    }
}
