<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Post::factory(5)->create()->each(function ($post) {
            Comment::factory(3)->forPost($post)->create()->each(function ($comment) {
                if ($comment->depth < Comment::MAX_DEPTH) {
                    Comment::factory(2)
                        ->forParent($comment)
                        ->create();
                }
            });
        });
    }
}
