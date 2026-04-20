<?php

namespace LaravelEnso\Comments\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use LaravelEnso\Users\Http\Resources\User;

class Comment extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'body'        => $this->body,
            'owner'       => new User($this->whenLoaded('createdBy')),
            'taggedUsers' => TaggedUser::collection($this->whenLoaded('taggedUsers')),
            'isEditable'  => $this->isEditable($request),
            'isDeletable' => $this->isDeletable($request),
            'createdAt'   => $this->created_at->toDatetimeString(),
            'updatedAt'   => $this->updated_at->toDatetimeString(),
        ];
    }

    public function isEditable($request)
    {
        return $request->user()->can('update', $this->resource);
    }

    public function isDeletable($request)
    {
        return $request->user()->can('destroy', $this->resource);
    }
}
