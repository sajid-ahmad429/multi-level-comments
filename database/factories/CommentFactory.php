<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => $this->faker->sentence,
            'post_id' => \App\Models\Post::factory(),
            'parent_comment_id' => null, // Root comment by default
            'depth' => 0, // Default depth for root comments
        ];
    }

    public function forpost(Post $post): self
    {
        return $this->state(function (array $attributes) use ($post) {
            return [
                'post_id' => $post->id,
            ];
        });
    }

    public function forParent(Comment $parent): self
    {
        return $this->state(function (array $attributes) use ($parent) {
            return [
                'parent_comment_id' => $parent->id,
                'depth' => $parent->depth + 1,
            ];
        });
    }
}
