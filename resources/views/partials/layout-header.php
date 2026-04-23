<?php
// /resources/views/partials/layout-header.php

use Src\Service\AuthService;
?>

<header class="sticky top-0 z-40 flex h-20 shrink-0 items-center justify-between gap-x-4 border-b border-gray-200 bg-white/80 backdrop-blur-md px-4 shadow-sm sm:gap-x-6 sm:px-8 dark:bg-gray-900/80 dark:border-gray-800 transition-all duration-300">

    <div class="flex items-center gap-x-4">
        <button @click="mobileMenuOpen = true" type="button" class="-m-2.5 p-2.5 text-secondary-700 lg:hidden dark:text-gray-200">
            <span class="sr-only">Open sidebar</span>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>

        <div class="flex items-center">
            <a href="<?= $baseUrl ?>" class="block sm:hidden">
                <img class="h-10 w-10 object-contain" src="<?= $assetBase ?>images/logo/logo.svg" alt="Logo">
            </a>

            <h1 class="hidden sm:block text-xl font-black tracking-tighter text-secondary-900 dark:text-white uppercase">
                <?= $appName ?>
            </h1>
        </div>
    </div>

    <div class="flex items-center gap-x-2 lg:gap-x-3">

        <button id="search-trigger" aria-label="Search" data-tooltip="Search (⌘K)"
            class="group p-3 rounded-2xl bg-white dark:bg-secondary-900 border border-secondary-100 dark:border-secondary-800 hover:border-primary-400 transition-all duration-300 shadow-sm text-secondary-400">
            <svg class="w-6 h-6 group-hover:scale-110 group-hover:text-primary-500 transition-all duration-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
        </button>

        <?php if ($isLoggedIn) : ?>

            <?php if (AuthService::isCat()) : ?>
                <button data-reset-button data-tooltip="DB Reset"
                    class="hidden md:block group p-3 rounded-2xl bg-white dark:bg-secondary-900 border border-secondary-100 dark:border-secondary-800 hover:border-primary-400 transition-all duration-300 shadow-sm text-secondary-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            <?php endif; ?>

            <?php if (AuthService::isAdmin()) : ?>
                <div class="relative group">
                    <button id="messages-btn" aria-label="Messages" data-tooltip="Messages"
                        class="p-3 rounded-2xl bg-white dark:bg-secondary-900 border border-secondary-100 dark:border-secondary-800 hover:border-primary-400 transition-all duration-300 shadow-sm text-secondary-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 group-hover:scale-110 transition-transform">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25H4.5a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0L12 13.5 2.25 6.75" />
                        </svg>
                    </button>
                    <span id="messages-badge" class="absolute -top-1 -right-1 bg-primary-400 text-white text-[10px] font-black h-5 w-5 flex items-center justify-center rounded-full hidden border-2 border-white dark:border-secondary-900 shadow-lg"></span>
                </div>
            <?php endif; ?>

            <div class="relative group">
                <button id="notifications-btn" aria-label="Notifications" data-tooltip="Notifications"
                    class="p-3 rounded-2xl bg-white dark:bg-secondary-900 border border-secondary-100 dark:border-secondary-800 hover:border-primary-400 transition-all duration-300 shadow-sm text-secondary-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 group-hover:animate-swing transition-transform">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                </button>
                <span id="notifications-badge" class="absolute top-2.5 right-3 w-2.5 h-2.5 bg-red-500 border-2 border-white dark:border-secondary-900 rounded-full hidden"></span>
            </div>

            <div class="relative group">
                <button id="chats-btn" aria-label="Chats" data-tooltip="Chats"
                    class="p-3 rounded-2xl bg-white dark:bg-secondary-900 border border-secondary-100 dark:border-secondary-800 hover:border-primary-400 transition-all duration-300 shadow-sm text-secondary-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 group-hover:scale-110 transition-transform">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </button>
                <span id="chats-badge" class="absolute -top-1 -right-1 bg-primary-400 text-white text-[10px] font-black h-5 w-5 flex items-center justify-center rounded-full hidden border-2 border-white dark:border-secondary-900 shadow-lg"></span>
            </div>

            <button data-tooltip="History" id="history-btn"
                class="group p-3 rounded-2xl bg-white dark:bg-secondary-900 border border-secondary-100 dark:border-secondary-800 hover:border-primary-400 transition-all duration-300 shadow-sm text-secondary-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 group-hover:rotate-12 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </button>

            <button data-tooltip="Settings" id="settings-btn"
                class="hidden group p-3 rounded-2xl bg-white dark:bg-secondary-900 border border-secondary-100 dark:border-secondary-800 hover:border-primary-400 transition-all duration-300 shadow-sm text-secondary-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 group-hover:rotate-45 transition-transform duration-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a7.75 7.75 0 0 1 0 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 0 1 0-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281Z" />
                </svg>
            </button>
        <?php endif ?>

        <button id="dark-toggle"
            class="group p-3 rounded-2xl bg-white dark:bg-secondary-900 border border-secondary-100 dark:border-secondary-800 hover:border-primary-400 transition-all duration-300 shadow-sm"
            title="Toggle Theme">
            <svg class="w-6 h-6 text-secondary-400 block dark:hidden group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
            </svg>
            <svg class="w-6 h-6 text-primary-400 hidden dark:block group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
</header>