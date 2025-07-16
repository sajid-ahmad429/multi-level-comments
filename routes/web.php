<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use Illuminate\Support\Facades\View;
use App\Livewire\Comments\CommentItem;
use App\Livewire\Posts\PostIndex;
use App\Livewire\Posts\PostShow;
use App\Livewire\Comments\CommentList;
use App\Livewire\Comments\CommentForm;
use App\Livewire\Comments\CommentReplyForm;
use App\Models\Post;


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::prefix('posts')->group(function () {
        Route::get('/', PostIndex::class)->name('posts.index');
        // Show a single post with its comments
        Route::get('/{post}', PostShow::class)->name('posts.show');
    });

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
