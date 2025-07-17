<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Comment;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Register the command to delete empty comments
Artisan::command('comments:delete-empty', function () {
    $deletedCount = \App\Models\Comment::whereNull('content')
        ->orWhere('content', '')
        ->delete();
    
    if ($deletedCount > 0) {
        $this->info("Deleted {$deletedCount} empty comments.");
    } else {
        $this->info('No empty comments found to delete.');
    }
})->purpose('Delete empty comments from the database');

Schedule::command('comments:delete-empty')
    ->everyMinute()
    ->sendOutputTo(storage_path('logs/delete-empty-comments.log'))
    ->emailOutputOnFailure(env('ADMIN_EMAIL'), 'Empty Comments Deletion Failed')
    ->description('Delete empty comments every minute and log the output')
    ->when(function () {
        // Only run if there are empty comments
        return Comment::whereNull('content')->orWhere('content', '')->exists();
    })
    ->onFailure(function () {
        // Handle failure, e.g., send an alert
        \Log::error('Failed to delete empty comments.');
    })
    ->onSuccess(function () {
        // Handle success, e.g., log success
        \Log::info('Successfully deleted empty comments.');
    })
    ->runInBackground()
    ->withoutOverlapping()
    ->timezone('Asia/Kolkata');