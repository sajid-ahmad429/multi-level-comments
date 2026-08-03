<?php

use App\Livewire\Posts\PostIndex;
use App\Models\Post;
use App\Models\User;
use Livewire\Livewire;

test('authenticated users can create, update, and delete posts from the livewire post index', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(PostIndex::class)
        ->set('title', 'My first post')
        ->set('content', 'This is the post body')
        ->call('createPost')
        ->assertHasNoErrors()
        ->assertSet('title', '')
        ->assertSet('content', '');

    $this->assertDatabaseHas('posts', [
        'title' => 'My first post',
        'content' => 'This is the post body',
    ]);

    $post = Post::query()->first();

    Livewire::test(PostIndex::class)
        ->set('editingPostId', $post->id)
        ->set('title', 'Updated post title')
        ->set('content', 'Updated post body')
        ->call('updatePost')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => 'Updated post title',
        'content' => 'Updated post body',
    ]);

    Livewire::test(PostIndex::class)
        ->call('deletePost', $post->id)
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('posts', [
        'id' => $post->id,
    ]);
});
