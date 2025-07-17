<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Comment;

/**
 * Command to delete empty comments from the database.
 */

class DeleteEmptyComments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-empty-comments';
    

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete empty comments from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if the comments table exists
        if (!Schema::hasTable('comments')) {
            $this->error('The comments table does not exist.');
            return;
        }

        // Fetch all empty comments
        $emptyComments = Comment::whereNull('content')->orWhere('content', '')->get();

        // Check if there are any empty comments to delete
        if ($emptyComments->isEmpty()) {
            $this->info('No empty comments found.');
            return;
        }

        // Delete empty comments
        foreach ($emptyComments as $comment) {
            $comment->delete();
        }

        $this->info('Empty comments deleted successfully.');
    }
}
