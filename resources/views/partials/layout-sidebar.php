<?php
// /resources/views/partials/sidebar.php
declare(strict_types=1);

use Src\Config\NavigationConfig;

$navLinks = NavigationConfig::getNavLinks($isLoggedIn);
$icons = NavigationConfig::getIcons();

$currentUrlTrimmed = rtrim((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", '/');

$displayName = 'Account';
if ($isLoggedIn && isset($currentUser)) {
    $parts = explode(' ', $currentUser->full_name);
    if (count($parts) > 1) {
        $displayName = strtoupper(substr($parts[0], 0, 1)) . '. ' . $parts[count($parts) - 1];
    } else {
        $displayName = $parts[0];
    }
    $initial = strtoupper(substr($currentUser->full_name, 0, 1));
}
?>

<style>
    /* High-Tech Scrollbar with "Ghost" Indicator */
    .no-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .no-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    /* 1. The "Ghost" State: Always slightly visible so users know it's there */
    .no-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(156, 163, 175, 0.08);
        border-radius: 20px;
        transition: background 0.3s ease;
    }

    /* 2. The "Active" State: Lights up when the nav area is hovered */
    nav:hover.no-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(156, 163, 175, 0.25);
    }

    /* 3. The "Interaction" State: Darker when they actually grab the bar */
    .no-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(156, 163, 175, 0.5) !important;
    }

    /* Dark Mode refinement: Using a lower contrast ghost for dark backgrounds */
    .dark .no-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.05);
    }

    .dark nav:hover.no-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.15);
    }
</style>

<div x-show="mobileMenuOpen" class="fixed inset-0 z-50 lg:hidden" role="dialog" aria-modal="true" x-cloak>
    <div class="fixed inset-0 bg-secondary-900/80 backdrop-blur-sm"
        x-show="mobileMenuOpen"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="mobileMenuOpen = false"></div>

    <div class="fixed inset-y-0 left-0 flex w-full max-w-xs flex-col bg-white dark:bg-gray-900 shadow-xl"
        x-show="mobileMenuOpen"
        x-transition:enter="transition ease-in-out duration-300 transform"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in-out duration-300 transform"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full">

        <div class="flex items-center justify-between px-6 h-32 shrink-0 border-b dark:border-gray-800">
            <div class="flex-1 flex justify-center">
                <a href="<?= $baseUrl ?>" class="flex items-center">
                    <div class="h-20 w-20 rounded-full overflow-hidden border-2 border-primary-400 p-1 bg-white dark:bg-gray-800">
                        <img class="h-full w-full rounded-full object-cover block dark:hidden" src="<?= $assetBase ?>images/logo/logo-compact-light.png" alt="Logo">
                        <img class="h-full w-full rounded-full object-cover hidden dark:block" src="<?= $assetBase ?>images/logo/logo-compact-dark.png" alt="Logo">
                    </div>
                </a>
            </div>
            <button @click="mobileMenuOpen = false" class="p-2 text-gray-400 hover:text-primary-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto p-4 space-y-1">
            <?php foreach ($navLinks as $name => $url): ?>
                <?php
                $isActive = ($currentUrlTrimmed === rtrim($url, '/'));
                $classes = "sidebar-nav-link group flex items-center px-4 py-3 text-base font-medium rounded-xl transition-all duration-200";
                $classes .= $isActive ? " bg-primary-400 text-white shadow-lg shadow-primary-400/30" : " text-secondary-700 hover:bg-primary-50 hover:text-primary-500 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-primary-400";
                ?>
                <a href="<?= $url ?>" data-partial data-title="<?= $name ?>" @click="mobileMenuOpen = false" class="<?= $classes ?>">
                    <div class="nav-icon mr-4 shrink-0 <?= $isActive ? 'text-white' : 'text-gray-400 group-hover:text-primary-400' ?>">
                        <?= $icons[$name] ?? '' ?>
                    </div>
                    <span class="truncate"><?= $name ?></span>
                </a>
            <?php endforeach; ?>

            <div class="h-24 lg:hidden"></div>
        </nav>

        <div class="p-4 border-t dark:border-gray-800 bg-white dark:bg-gray-900 shrink-0">
            <?php if ($isLoggedIn): ?>
                <a href="<?= $baseUrl ?>logout" data-logout-button class="flex items-center gap-x-4 rounded-2xl p-4 bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800">
                    <div class="h-10 w-10 rounded-full border-2 border-primary-400 bg-white dark:bg-gray-900 flex items-center justify-center text-primary-500 font-black shadow-sm">
                        <?= $initial ?? 'U' ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-secondary-900 dark:text-white truncate text-xs font-black tracking-tight"><?= htmlspecialchars($displayName) ?></p>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 truncate uppercase font-bold tracking-widest">Sign out</p>
                    </div>
                </a>
            <?php else: ?>
                <a href="<?= $baseUrl ?>login" data-login-button class="flex items-center gap-x-4 rounded-2xl p-4 bg-primary-400 text-white shadow-lg">
                    <div class="h-10 w-10 rounded-full bg-white/20 flex items-center justify-center text-white font-black text-xs">G</div>
                    <div class="flex-1">
                        <p class="truncate text-white text-xs font-black uppercase tracking-tighter">Guest Mode</p>
                        <p class="text-[10px] text-primary-100 truncate font-bold uppercase">Sign in</p>
                    </div>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:flex-col transition-all duration-300 ease-in-out"
    x-cloak :class="$store.sidebar.expanded ? 'lg:w-64' : 'lg:w-24'">

    <div class="flex flex-col h-full overflow-hidden border-r border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 transition-all duration-300"
        :class="$store.sidebar.expanded ? 'px-6' : 'px-3'">

        <div class="flex shrink-0 items-center justify-between relative transition-all duration-300"
            :class="$store.sidebar.expanded ? 'h-48' : 'h-36 flex-col py-8'">

            <div class="flex-1 flex justify-center items-center">
                <a href="<?= $baseUrl ?>" class="flex items-center justify-center group">
                    <div x-show="$store.sidebar.expanded" x-transition.opacity class="flex justify-center">
                        <div class="h-32 w-32 rounded-full overflow-hidden border-4 border-primary-400 shadow-2xl transition-transform group-hover:scale-105 duration-300 bg-white dark:bg-gray-800 p-1">
                            <img class="h-full w-full rounded-full object-cover block dark:hidden" src="<?= $assetBase ?>images/logo/logo-light.png" alt="Logo">
                            <img class="h-full w-full rounded-full object-cover hidden dark:block" src="<?= $assetBase ?>images/logo/logo-dark.png" alt="Logo">
                        </div>
                    </div>
                    <div x-show="!$store.sidebar.expanded" x-transition.opacity x-cloak class="flex justify-center">
                        <div class="h-16 w-16 rounded-full overflow-hidden border-2 border-primary-400 shadow-xl transition-all group-hover:scale-110 bg-white dark:bg-gray-800 p-0.5">
                            <img class="h-full w-full rounded-full object-cover block dark:hidden" src="<?= $assetBase ?>images/logo/logo-compact-light.png" alt="Icon">
                            <img class="h-full w-full rounded-full object-cover hidden dark:block" src="<?= $assetBase ?>images/logo/logo-compact-dark.png" alt="Icon">
                        </div>
                    </div>
                </a>
            </div>

            <button @click="$store.sidebar.toggle()"
                class="group p-1.5 rounded-full bg-white dark:bg-gray-800 text-gray-400 hover:text-primary-400 border border-gray-200 dark:border-gray-700 transition-all shadow-sm hover:shadow-md"
                :class="$store.sidebar.expanded ? 'absolute -right-3 top-1/2 -translate-y-1/2 z-10' : 'relative mt-4'">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-500" :class="!$store.sidebar.expanded && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
            </button>
        </div>

        <nav class="flex flex-1 flex-col overflow-y-auto no-scrollbar pb-4">
            <ul role="list" class="flex flex-1 flex-col gap-y-2">
                <?php foreach ($navLinks as $name => $url): ?>
                    <?php $isActive = ($currentUrlTrimmed === rtrim($url, '/')); ?>
                    <li class="relative group" :title="!$store.sidebar.expanded ? '<?= $name ?>' : ''">
                        <a href="<?= $url ?>" data-partial data-title="<?= $name ?>"
                            class="sidebar-nav-link group flex gap-x-3 rounded-xl p-3 text-sm font-semibold leading-6 transition-all duration-200 
                            <?= $isActive ? 'bg-primary-400 text-white shadow-lg shadow-primary-400/30' : 'text-secondary-700 hover:text-primary-500 hover:bg-primary-50 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-primary-400' ?>"
                            :class="!$store.sidebar.expanded && 'justify-center'">
                            <div class="nav-icon shrink-0 <?= $isActive ? 'text-white' : 'text-gray-400 group-hover:text-primary-400' ?>">
                                <?= $icons[$name] ?? '' ?>
                            </div>
                            <span x-show="$store.sidebar.expanded" class="truncate" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-[-10px]" x-transition:enter-end="opacity-100 translate-x-0">
                                <?= $name ?>
                            </span>
                        </a>
                        <div x-show="!$store.sidebar.expanded" class="absolute left-full top-1/2 -translate-y-1/2 ml-4 px-3 py-1.5 bg-secondary-900 text-white text-xs font-bold rounded-lg opacity-0 group-hover:opacity-100 pointer-events-none whitespace-nowrap z-[100] transition-opacity duration-200 shadow-2xl border border-white/10">
                            <?= $name ?>
                            <div class="absolute right-full top-1/2 -translate-y-1/2 border-8 border-transparent border-r-secondary-900"></div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <div class="mt-auto shrink-0 -mx-2 pb-6 pt-4 border-t border-gray-100 dark:border-gray-800">
            <?php if ($isLoggedIn): ?>
                <div class="relative group">
                    <a href="<?= $baseUrl ?>logout" data-logout-button
                        :title="!$store.sidebar.expanded ? 'Sign out' : ''"
                        class="group flex items-center gap-x-3 rounded-2xl p-3 text-sm font-semibold leading-6 bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800 transition-all duration-200 hover:bg-red-50 dark:hover:bg-red-950/20"
                        :class="!$store.sidebar.expanded && 'justify-center border-none bg-transparent'">
                        <div class="h-10 w-10 rounded-full border-2 border-primary-400 bg-white dark:bg-gray-900 flex items-center justify-center text-primary-500 font-black shrink-0 shadow-sm group-hover:scale-110 transition-transform">
                            <?= $initial ?? 'U' ?>
                        </div>
                        <div x-show="$store.sidebar.expanded" class="flex-1 min-w-0">
                            <p class="text-secondary-900 dark:text-white truncate text-xs font-black tracking-tight"><?= htmlspecialchars($displayName) ?></p>
                            <p class="text-[10px] text-gray-500 dark:text-gray-400 truncate uppercase font-bold tracking-widest">Sign out</p>
                        </div>
                    </a>
                </div>
            <?php else: ?>
                <div class="relative group">
                    <a href="<?= $baseUrl ?>login" data-login-button
                        :title="!$store.sidebar.expanded ? 'Sign in' : ''"
                        class="group flex items-center gap-x-3 rounded-2xl p-3 text-sm font-semibold leading-6 bg-primary-400 text-white shadow-lg hover:bg-primary-500 transition-all"
                        :class="!$store.sidebar.expanded && 'justify-center'">
                        <div class="h-10 w-10 rounded-full bg-white/20 flex items-center justify-center text-white font-black shrink-0 text-xs">G</div>
                        <div x-show="$store.sidebar.expanded" class="flex-1">
                            <p class="truncate text-white text-xs font-black uppercase tracking-tighter">Guest Mode</p>
                            <p class="text-[10px] text-primary-100 truncate font-bold uppercase">Sign in</p>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>