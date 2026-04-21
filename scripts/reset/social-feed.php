<?php
// /scripts/reset/social-feed.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostLike;
use App\Models\Follow;

/**
 * Resets all social feed tables with Foreign Key Cascades 🍊
 */
function resetSocialFeedTables(): array
{
    $messages = [];

    try {
        $schema = Capsule::schema();

        // 1. Drop existing tables in reverse order of dependencies
        $schema->dropIfExists((new PostLike())->getTable());
        $schema->dropIfExists((new PostComment())->getTable());
        $schema->dropIfExists((new Post())->getTable());
        $schema->dropIfExists((new Follow())->getTable());
        $messages[] = "dropped existing social feed tables";

        // 2. Create Follows Table
        $schema->create((new Follow())->getTable(), function (Blueprint $table) {
            $table->increments('follow_id');
            $table->unsignedInteger('follower_id');
            $table->unsignedInteger('following_id');
            $table->timestamps();
            $table->index(['follower_id', 'following_id']);
        });
        $messages[] = "created follows table";

        // 3. Create Posts Table
        $schema->create((new Post())->getTable(), function (Blueprint $table) {
            $table->increments('post_id');
            $table->unsignedBigInteger('orig_user_id')->index();
            $table->text('content')->nullable();
            $table->string('media_url')->nullable();
            $table->enum('media_type', ['image', 'video', 'none'])->default('none');
            $table->timestamps();
        });
        $messages[] = "created posts table";

        // 4. Create Post Comments Table with CASCADE 🍊
        $schema->create((new PostComment())->getTable(), function (Blueprint $table) {
            $table->increments('comment_id');
            $table->unsignedInteger('post_id');
            $table->unsignedBigInteger('orig_user_id')->index();
            $table->text('comment_text');
            $table->timestamps();

            // Foreign Key Constraint
            $table->foreign('post_id')
                ->references('post_id')
                ->on('posts')
                ->onDelete('cascade');

            $table->index('post_id');
        });
        $messages[] = "created post_comments table (with cascade)";

        // 5. Create Post Likes Table with CASCADE 🍊
        $schema->create((new PostLike())->getTable(), function (Blueprint $table) {
            $table->increments('like_id');
            $table->unsignedInteger('post_id');
            $table->unsignedBigInteger('orig_user_id')->index();
            $table->timestamps();

            // Foreign Key Constraint
            $table->foreign('post_id')
                ->references('post_id')
                ->on('posts')
                ->onDelete('cascade');

            $table->unique(['post_id', 'orig_user_id']);
            $table->index('post_id');
        });
        $messages[] = "created post_likes table (with cascade)";
    } catch (\Throwable $e) {
        $messages[] = 'social feed table error: ' . $e->getMessage();
    }

    return $messages;
}
