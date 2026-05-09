<?php
// /server/api/reset.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;

header('Content-Type: application/json');

$messages = [];

/**
 * 1. PRE-FLIGHT CHECKS & DISABLE CONSTRAINTS
 */
Capsule::schema()->disableForeignKeyConstraints();

/**
 * 2. AGGRESSIVE DROP PHASE (Children first, then Parents)
 * This ensures we don't have "ghost" tables blocking the reset scripts.
 */
$tablesToDrop = [
    // Gonachi Swap System 🔄
    'listings_pics',
    'listings',
    'listing_types',
    'listing_conditions',
    'listing_categories',

    // Chats System
    'conversation_messages',
    'conversations',

    // Social Feed Children
    'post_likes',
    'post_comments',
    'social_posts',
    'follows',

    // Authentication & Core
    'password_resets',
    'user_verifications',
    'notifications',
    'messages',
    'recent_activities',
    'users',
    'user_types',

    // Static/Lookup Tables
    'countries',
    'regions',
    'faqs',
];

foreach ($tablesToDrop as $table) {
    Capsule::schema()->dropIfExists($table);
}

$messages[] = "database cleared: All dependent and parent tables dropped.";

/**
 * 3. CREATION PHASE - LEVEL 1: LOOKUPS & INDEPENDENT PARENTS
 * These must exist first because other tables reference them.
 */

// Core Users & Types
require_once __DIR__ . '/../../scripts/reset/user-types.php';
$messages = array_merge($messages, resetUserTypesTable());

require_once __DIR__ . '/../../scripts/reset/countries.php';
$messages = array_merge($messages, resetCountriesTable());

require_once __DIR__ . '/../../scripts/reset/regions.php';
$messages = array_merge($messages, resetRegionsTable());

require_once __DIR__ . '/../../scripts/reset/users.php';
$messages = array_merge($messages, resetUsersTable());

// Gonachi Swap Lookups 🏷️
require_once __DIR__ . '/../../scripts/reset/listing-categories.php';
$messages = array_merge($messages, resetListingCategoriesTable());

require_once __DIR__ . '/../../scripts/reset/listing-types.php';
$messages = array_merge($messages, resetListingTypesTable());

require_once __DIR__ . '/../../scripts/reset/listing-conditions.php';
$messages = array_merge($messages, resetListingConditionsTable());

/**
 * 4. CREATION PHASE - LEVEL 2: FUNCTIONAL SYSTEMS
 */

// Chats System 💎
require_once __DIR__ . '/../../scripts/reset/conversations.php';
$messages = array_merge($messages, resetConversationsTable());

require_once __DIR__ . '/../../scripts/reset/conversation-messages.php';
$messages = array_merge($messages, resetConversationMessagesTable());

// Social Feed
require_once __DIR__ . '/../../scripts/reset/social-feed.php';
$messages = array_merge($messages, resetSocialFeedTables());

// Gonachi Swap Marketplace 🔄
require_once __DIR__ . '/../../scripts/reset/listings.php';
$messages = array_merge($messages, resetListingsTable());

require_once __DIR__ . '/../../scripts/reset/listing-pics.php';
$messages = array_merge($messages, resetListingPicsTable());

// Support
require_once __DIR__ . '/../../scripts/reset/recent-activities.php';
$messages = array_merge($messages, resetRecentActivitiesTable());

require_once __DIR__ . '/../../scripts/reset/faqs.php';
$messages = array_merge($messages, resetFaqsTable());

require_once __DIR__ . '/../../scripts/reset/password-resets.php';
$messages = array_merge($messages, resetPasswordResetsTable());

// User Account Activation (Registration)
require_once __DIR__ . '/../../scripts/reset/user-verifications.php';
$messages = array_merge($messages, resetUserVerificationsTable());

require_once __DIR__ . '/../../scripts/reset/messages.php';
$messages = array_merge($messages, resetMessagesTable());

require_once __DIR__ . '/../../scripts/reset/notifications.php';
$messages = array_merge($messages, resetNotificationsTable());

/**
 * 6. FINALIZE
 */
Capsule::schema()->enableForeignKeyConstraints();

$deleteAllPicturesAndPDFs = true;

// --- DELETE specific social media content only ---
if ($deleteAllPicturesAndPDFs) {
    // Specifically target ONLY the folders that store transient post data 🍊
    $targetFolders = [
        __DIR__ . '/../../public/images/uploads',
        __DIR__ . '/../../public/videos',
    ];

    foreach ($targetFolders as $folder) {
        $resolved = realpath($folder);

        // Skip if the folder doesn't exist to avoid errors
        if ($resolved === false || !is_dir($resolved)) {
            $messages[] = "Skipping: folder not found: $folder";
            continue;
        }

        $messages[] = "cleaning contents of: $resolved";

        $entries = scandir($resolved);
        if ($entries === false) continue;

        $deletedCount = 0;
        foreach ($entries as $entry) {
            // NEVER delete current, parent, or .gitkeep (keeps the folder structure in Git)
            if (in_array($entry, ['.', '..', '.gitkeep'])) continue;

            $path = $resolved . DIRECTORY_SEPARATOR . $entry;

            if (is_dir($path)) {
                // If it's a subfolder inside posts, delete it and its contents
                if (rrmdir($path)) $deletedCount++;
            } else {
                // If it's a file (like an image/video), delete it
                if (unlink($path)) $deletedCount++;
            }
        }

        $messages[] = "purged $deletedCount item(s) from $folder. (Avatars preserved 🍊)";
    }
}

json_response(['success' => true, 'messages' => $messages]);
