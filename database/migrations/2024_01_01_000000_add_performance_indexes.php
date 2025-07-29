<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes to posts table for better query performance
        Schema::table('posts', function (Blueprint $table) {
            // Index for search queries
            $table->index(['title', 'created_at'], 'posts_title_created_idx');
            
            // Index for user posts
            if (Schema::hasColumn('posts', 'user_id')) {
                $table->index('user_id', 'posts_user_id_idx');
            }
            
            // Index for published posts (if column exists)
            if (Schema::hasColumn('posts', 'published_at')) {
                $table->index('published_at', 'posts_published_at_idx');
            }
            
            // Composite index for common queries
            $table->index(['created_at', 'updated_at'], 'posts_dates_idx');
        });

        // Add indexes to comments table for better query performance
        Schema::table('comments', function (Blueprint $table) {
            // Index for post comments
            $table->index(['post_id', 'created_at'], 'comments_post_created_idx');
            
            // Index for threaded comments
            $table->index(['parent_comment_id', 'depth'], 'comments_thread_idx');
            
            // Index for user comments
            if (Schema::hasColumn('comments', 'user_id')) {
                $table->index('user_id', 'comments_user_id_idx');
            }
            
            // Index for depth-based queries
            $table->index('depth', 'comments_depth_idx');
        });

        // Add indexes to cache table if using database cache
        if (Schema::hasTable('cache')) {
            Schema::table('cache', function (Blueprint $table) {
                $table->index('expires_at', 'cache_expires_at_idx');
            });
        }

        // Add indexes to sessions table if using database sessions
        if (Schema::hasTable('sessions')) {
            Schema::table('sessions', function (Blueprint $table) {
                $table->index('last_activity', 'sessions_last_activity_idx');
                if (Schema::hasColumn('sessions', 'user_id')) {
                    $table->index('user_id', 'sessions_user_id_idx');
                }
            });
        }

        // Add indexes to jobs table for queue performance
        if (Schema::hasTable('jobs')) {
            Schema::table('jobs', function (Blueprint $table) {
                $table->index(['queue', 'available_at'], 'jobs_queue_available_idx');
                $table->index('available_at', 'jobs_available_at_idx');
            });
        }

        // Add indexes to failed_jobs table
        if (Schema::hasTable('failed_jobs')) {
            Schema::table('failed_jobs', function (Blueprint $table) {
                $table->index('failed_at', 'failed_jobs_failed_at_idx');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes from posts table
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex('posts_title_created_idx');
            if (Schema::hasColumn('posts', 'user_id')) {
                $table->dropIndex('posts_user_id_idx');
            }
            if (Schema::hasColumn('posts', 'published_at')) {
                $table->dropIndex('posts_published_at_idx');
            }
            $table->dropIndex('posts_dates_idx');
        });

        // Drop indexes from comments table
        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex('comments_post_created_idx');
            $table->dropIndex('comments_thread_idx');
            if (Schema::hasColumn('comments', 'user_id')) {
                $table->dropIndex('comments_user_id_idx');
            }
            $table->dropIndex('comments_depth_idx');
        });

        // Drop indexes from cache table
        if (Schema::hasTable('cache')) {
            Schema::table('cache', function (Blueprint $table) {
                $table->dropIndex('cache_expires_at_idx');
            });
        }

        // Drop indexes from sessions table
        if (Schema::hasTable('sessions')) {
            Schema::table('sessions', function (Blueprint $table) {
                $table->dropIndex('sessions_last_activity_idx');
                if (Schema::hasColumn('sessions', 'user_id')) {
                    $table->dropIndex('sessions_user_id_idx');
                }
            });
        }

        // Drop indexes from jobs table
        if (Schema::hasTable('jobs')) {
            Schema::table('jobs', function (Blueprint $table) {
                $table->dropIndex('jobs_queue_available_idx');
                $table->dropIndex('jobs_available_at_idx');
            });
        }

        // Drop indexes from failed_jobs table
        if (Schema::hasTable('failed_jobs')) {
            Schema::table('failed_jobs', function (Blueprint $table) {
                $table->dropIndex('failed_jobs_failed_at_idx');
            });
        }
    }
};