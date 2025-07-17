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
    ->sendOutputTo(storage_path('logs/delete-empty-comments.log'));