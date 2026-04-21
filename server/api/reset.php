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
    // Chats System
    'conversation_messages',
    'conversations',

    // Social Feed Children
    //'post_likes',
    //'post_comments',
    //'social_posts',
    //'follows',

    // Advert System Children
    //'advert_pics',
    //'adverts',
    //'advert_packages',
    //'advert_call_to_actions',

    // Handshake Responses (New)
    //'quotations-responses',
    //'listings-responses',

    // Quotation System Children
    //'quotation_pics',
    //'quotations',
    //'quotation_destinations',
    //'quotation_types',

    // Mentor System Children
    //'mentor_requests',
    //'mentors',

    // Listings System Children
    //'listings_pics',
    //'listings',
    //'amenities',
    //'available_parking',

    // Authentication & Core
    //'password_resets',
    //'user_verifications',
    //'notifications',
    //'messages',
    //'recent_activities',
    //'users',
    //'user_types',

    // Static/Lookup Tables
    //'countries',
    //'regions',
    //'faqs',
    //'house_types',
    //'unit_types',
    //'skilled_trades',
    //'contractor_types',
    //'agreement_types',
    //'bedrooms',
    //'bathrooms',
    //'basement_types',
    //'listings_categories_types',
    //'listings_categories'
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
//$messages = array_merge($messages, resetUserTypesTable());

require_once __DIR__ . '/../../scripts/reset/countries.php';
//$messages = array_merge($messages, resetCountriesTable());

require_once __DIR__ . '/../../scripts/reset/regions.php';
//$messages = array_merge($messages, resetRegionsTable());

require_once __DIR__ . '/../../scripts/reset/users.php';
//$messages = array_merge($messages, resetUsersTable());

// Advert Parents
require_once __DIR__ . '/../../scripts/reset/adverts-call-to-action.php';
//$messages = array_merge($messages, resetAdvertCtasTable());

require_once __DIR__ . '/../../scripts/reset/advert-packages.php';
//$messages = array_merge($messages, resetAdvertPackagesTable());

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
//$messages = array_merge($messages, resetSocialFeedTables());

// Adverts (Depends on Packages/CTAs)
require_once __DIR__ . '/../../scripts/reset/adverts.php';
//$messages = array_merge($messages, resetAdvertsTable());

require_once __DIR__ . '/../../scripts/reset/advert-pics.php';
//$messages = array_merge($messages, resetAdvertPicsTable());

// Support
require_once __DIR__ . '/../../scripts/reset/recent-activities.php';
//$messages = array_merge($messages, resetRecentActivitiesTable());

require_once __DIR__ . '/../../scripts/reset/faqs.php';
//$messages = array_merge($messages, resetFaqsTable());

require_once __DIR__ . '/../../scripts/reset/password-resets.php';
//$messages = array_merge($messages, resetPasswordResetsTable());

// User Account Activation (Registration)
require_once __DIR__ . '/../../scripts/reset/user-verifications.php';
//$messages = array_merge($messages, resetUserVerificationsTable());

require_once __DIR__ . '/../../scripts/reset/messages.php';
//$messages = array_merge($messages, resetMessagesTable());

require_once __DIR__ . '/../../scripts/reset/notifications.php';
//$messages = array_merge($messages, resetNotificationsTable());

/**
 * 5. CREATION PHASE - LEVEL 3: QUOTATIONS, MENTORS & LISTINGS
 */

// Quotations
require_once __DIR__ . '/../../scripts/reset/contractor-types.php';
//$messages = array_merge($messages, resetContractorTypesTable());

require_once __DIR__ . '/../../scripts/reset/skilled-trades.php';
//$messages = array_merge($messages, resetSkilledTradesTable());

require_once __DIR__ . '/../../scripts/reset/unit-types.php';
//$messages = array_merge($messages, resetUnitTypesTable());

require_once __DIR__ . '/../../scripts/reset/quotation-types.php';
//$messages = array_merge($messages, resetQuotationTypesTable());

require_once __DIR__ . '/../../scripts/reset/quotation-destinations.php';
//$messages = array_merge($messages, resetQuotationDestinationsTable());

require_once __DIR__ . '/../../scripts/reset/house-types.php';
//$messages = array_merge($messages, resetHouseTypesTable());

require_once __DIR__ . '/../../scripts/reset/quotations.php';
//$messages = array_merge($messages, resetQuotationsTable());

require_once __DIR__ . '/../../scripts/reset/quotation-pics.php';
//$messages = array_merge($messages, resetQuotationPicsTable());

require_once __DIR__ . '/../../scripts/reset/quotations-responses.php';
//$messages = array_merge($messages, resetQuotationResponsesTable());

// Mentors (Depends on Users)
require_once __DIR__ . '/../../scripts/reset/mentors.php';
//$messages = array_merge($messages, resetMentorsTable());

require_once __DIR__ . '/../../scripts/reset/mentor-requests.php';
//$messages = array_merge($messages, resetMentorsRequestsTable());

// Listings (Lookup Parents)
require_once __DIR__ . '/../../scripts/reset/listings-categories.php';
//$messages = array_merge($messages, resetListingsCategoriesTable());

require_once __DIR__ . '/../../scripts/reset/listings-categories-types.php';
//$messages = array_merge($messages, resetListingsCategoriesTypesTable());

require_once __DIR__ . '/../../scripts/reset/basement-types.php';
//$messages = array_merge($messages, resetBasementTypesTable());

require_once __DIR__ . '/../../scripts/reset/bathrooms.php';
//$messages = array_merge($messages, resetBathroomsTable());

require_once __DIR__ . '/../../scripts/reset/bedrooms.php';
//$messages = array_merge($messages, resetBedroomsTable());

require_once __DIR__ . '/../../scripts/reset/agreement-types.php';
//$messages = array_merge($messages, resetAgreementTypesTable());

require_once __DIR__ . '/../../scripts/reset/available-parking.php';
//$messages = array_merge($messages, resetAvailableParkingTable());

require_once __DIR__ . '/../../scripts/reset/amenities.php';
//$messages = array_merge($messages, resetAmenitiesTables());

// Final Listings
require_once __DIR__ . '/../../scripts/reset/listings.php';
//$messages = array_merge($messages, resetListingsTable());

require_once __DIR__ . '/../../scripts/reset/listings-pics.php';
//$messages = array_merge($messages, resetListingPicsTable());

require_once __DIR__ . '/../../scripts/reset/listings-responses.php';
//$messages = array_merge($messages, resetListingResponsesTable());

/**
 * 6. FINALIZE
 */
Capsule::schema()->enableForeignKeyConstraints();

$deleteAllPicturesAndPDFs = false;

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
