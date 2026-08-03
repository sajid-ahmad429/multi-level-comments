<?php

use App\Livewire\Comments\CommentForm;
use App\Livewire\Comments\CommentItem;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Livewire\Livewire;

test('authenticated users can create and render a comment without breaking the author relation', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $post = Post::factory()->create();

    Livewire::test(CommentForm::class, ['postId' => $post->id])
        ->set('content', 'This is a test comment.')
        ->call('submit')
        ->assertHasNoErrors();

    $comment = Comment::query()->first();

    expect($comment)->not->toBeNull();
    expect($comment->user_id)->toBe($user->id);

    Livewire::test(CommentItem::class, ['comment' => $comment, 'post' => $post])
        ->assertSee('This is a test comment.');
});
