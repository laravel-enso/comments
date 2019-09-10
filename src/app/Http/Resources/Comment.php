<?php

namespace LaravelEnso\Comments\app\Http\Resources;

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
            'taggedUsers' => TaggedUser::collection($this->whenLoaded('taggedUsers')),
            'isEditable' => $this->isEditable($request),
            'isDeletable' => $this->isDeletable($request),
            'createdAt' => $this->created_at->toDatetimeString(),
            'updatedAt' => $this->updated_at->toDatetimeString(),
        ];
    }

    public function isEditable($request)
    {
        return $request->user()
            && $request->user()->can('update', $this->resource);
    }

    public function isDeletable($request)
    {
        return $request->user()
            && $request->user()->can('destroy', $this->resource);
    }
}
