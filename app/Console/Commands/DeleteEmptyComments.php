<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Comment;
/**
 * Class DeleteEmptyComments
 *
 * This command is responsible for deleting empty comments from the application.
 */


class DeleteEmptyComments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'comments:delete-empty';

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
        $this->info('Starting to delete empty comments...');
        // Assuming the Comment model has a 'content' field that can be empty
        $deletedCount = Comment::whereNull('content')->orWhere('content', '')->delete();

        if ($deletedCount > 0) {
            $this->info("Deleted {$deletedCount} empty comments.");
        } else {
            $this->info('No empty comments found to delete.');
        }
    }
}
