<?php

namespace LaravelEnso\CommentsManager\app\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use LaravelEnso\TrackWho\app\Http\Resources\TrackWho;

class Comment extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'owner' => new TrackWho($this->whenLoaded('createdBy')),
            'taggedUsers' => $this->whenLoaded('taggedUsers', $this->taggedUserList()),
            'isEditable' => $this->isEditable(),
            'isDeletable' => $this->isDeletable(),
            'createdAt' => $this->created_at->toDatetimeString(),
            'updatedAt' => $this->updated_at->toDatetimeString(),
        ];
    }

    private function taggedUserList()
    {
        return $this->taggedUsers
            ->load('person')
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->person->name,
                ];
            });
    }
}
