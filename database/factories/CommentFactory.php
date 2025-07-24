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
        $indianComments = [
            'Bahut accha laga padhkar!',
            'Yeh information kaafi upyogi hai.',
            'Kya aap aur details de sakte hain?',
            'Mujhe yeh jagah dekhni hai!',
            'Swadisht khana lagta hai!',
            'Dhanyavaad, yeh post share karne ke liye.',
            'Bohot hi rochak jankari hai.',
            'Aapka likhne ka andaaz pasand aaya.',
            'Yahan ki tasveer bhi khoobsurat hai.',
            'Festivals ki baat hi kuch aur hai India mein.'
        ];

        $extra = $this->faker->optional()->sentence();
        $name = $this->faker->firstName();

        $content = $this->faker->randomElement($indianComments) . " $extra - $name";

        return [
            'content' => $content,
            'post_id' => \App\Models\Post::factory(),
            'parent_comment_id' => null,
            'depth' => 0,
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
