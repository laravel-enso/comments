<?php

namespace LaravelEnso\Comments\app\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaggedUser extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->person->name,
        ];
    }
}
