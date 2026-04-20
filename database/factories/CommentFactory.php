<?php

namespace LaravelEnso\Comments\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use LaravelEnso\Comments\Models\Comment;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition()
    {
        return [
            'commentable_id'   => $this->faker->randomKey,
            'commentable_type' => $this->faker->word,
            'body'             => $this->faker->sentence,
        ];
    }
}
