<?php
// /resources/views/pages/social-feed.php

declare(strict_types=1);

/** @var bool $isLoggedIn */

if ($isLoggedIn) {
    $controller = new \Src\Controller\SocialFeedController();
    $controller->index();

    $feedHtml = $GLOBALS['feedHtml'] ?? '';
?>

    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 px-4 pt-6 animate-in fade-in duration-700">

        <div class="lg:col-span-8 space-y-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 animate-in slide-in-from-left-8 duration-700">
                <div class="flex-1">
                    <h1 class="text-2xl font-black text-secondary-900 dark:text-white font-sans tracking-tight">Social Feed</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 font-medium">
                        See what's happening across the stack. Share updates, photos, and videos.
                    </p>
                </div>

                <div class="mt-4 md:mt-0">
                    <button type="button" id="create-post-btn"
                        class="w-full flex items-center justify-center rounded-xl bg-primary-500 px-6 py-2.5 text-sm font-black text-white shadow-lg shadow-primary-500/20 hover:bg-primary-600 transition-all active:scale-95">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span>Share Post</span>
                    </button>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-800 p-6 transition-all hover:shadow-xl hover:shadow-gray-200/50 dark:hover:shadow-none animate-in slide-in-from-top-4 duration-700 delay-150">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="h-12 w-12 rounded-2xl bg-primary-50 dark:bg-primary-900/20 flex items-center justify-center text-primary-500 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 4v16m8-8H4" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div id="shortcut-post-trigger" class="flex-1 bg-gray-50 dark:bg-gray-800/50 rounded-2xl px-5 py-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition-all group border border-transparent hover:border-primary-200/50">
                        <span class="text-gray-400 dark:text-gray-500 font-sans italic text-sm group-hover:text-gray-600">What's on your mind?</span>
                    </div>
                </div>

                <div class="flex items-center gap-4 border-t border-gray-50 dark:border-gray-800/50 pt-4 px-2">
                    <button type="button" id="intent-image-btn" class="flex items-center space-x-2 text-gray-500 dark:text-gray-400 hover:bg-primary-50 dark:hover:bg-primary-900/20 hover:text-primary-500 px-4 py-2 rounded-xl transition-all group">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-xs font-black uppercase tracking-widest">Photo</span>
                    </button>

                    <button type="button" id="intent-video-btn" class="flex items-center space-x-2 text-gray-500 dark:text-gray-400 hover:bg-secondary-50 dark:hover:bg-secondary-900/20 hover:text-secondary-600 px-4 py-2 rounded-xl transition-all group">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <span class="text-xs font-black uppercase tracking-widest">Video</span>
                    </button>
                </div>
            </div>

            <div id="social-feed-container" class="space-y-6 pb-12">
                <?php if (empty($feedHtml)): ?>
                    <div id="empty-feed-msg" class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-20 text-center border border-gray-100 dark:border-gray-800 animate-in zoom-in-95 duration-700">
                        <div class="flex flex-col items-center">
                            <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-full mb-6 animate-float">
                                <svg class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                            </div>
                            <p class="font-black text-secondary-900 dark:text-white text-xl tracking-tight">Your feed is quiet...</p>
                            <p class="text-gray-400 text-sm mt-2 font-medium">Follow some colleagues or share your first update!</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?= $feedHtml ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="lg:col-span-4 animate-in slide-in-from-right-8 duration-700 delay-300">
            <?php include __DIR__ . '/../components/social-feed/sidebar.php'; ?>
        </div>

    </div>

<?php
    include __DIR__ . '/../components/social-feed/view-post-modal.php';
} else {
    include __DIR__ . '/../components/social-feed/guest-landing.php';
}
