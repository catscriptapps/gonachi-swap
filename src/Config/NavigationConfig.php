<?php

declare(strict_types=1);

namespace Src\Config;

use Src\Service\AuthService;

/**
 * NavigationConfig handles all static data related to the application's
 * primary navigation structure, including link URLs and associated icons.
 */
class NavigationConfig
{
    /**
     * Defines the icon mapping for each navigation link name.
     * @return array<string, string>
     */
    public static function getIcons(): array
    {
        return [
            'Home'                  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9v9a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-4H9v4a2 2 0 0 1-2 2H3v-9z" /></svg>',
            'About'                 => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20z" /></svg>',
            'Contact'               => '<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.0"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>',
            'Dashboard'             => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h7v7H3V3zm0 11h7v7H3v-7zm11-11h7v7h-7V3zm0 11h7v7h-7v-7z"/></svg>',
            'Listings'              => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>',
            'Saved'                 => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" /></svg>',
            'Chats'                 => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>',
            'Social Feed'           => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>',
            'Profile'               => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>',
            'Users'                 => '<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75a3 3 0 11-6 0 3 3 0 016 0zM6.75 6.75a3 3 0 116 0 3 3 0 01-6 0zM3 21a6 6 0 0112 0M9 21a6 6 0 0112 0"></path></svg>',
        ];
    }

    /**
     * Returns the navigation links for the current user.
     */
    public static function getNavLinks(bool $isLoggedIn): array
    {
        return $isLoggedIn ? self::authLinks() : self::publicLinks();
    }

    /**
     * Returns all auth-only links.
     * If $showAll is true, it ignores permissions (used for Admin checkbox list).
     */
    public static function authLinks(bool $showAll = false): array
    {
        $base = $_ENV['APP_BASE_PATH'] ?? '';

        $allPossibleApps = [
            'Dashboard'             => $base . '/dashboard',
            'Listings'              => $base . '/listings',
            'Saved'                 => $base . '/saved',
            'Chats'                 => $base . '/chats',
            'Social Feed'           => $base . '/social-feed',
            'Profile'               => $base . '/profile',
            'Users'                 => $base . '/users',
        ];

        if ($showAll) {
            return $allPossibleApps;
        }

        $visibleLinks = [];
        foreach ($allPossibleApps as $name => $path) {
            // Check real-time permission via AuthService
            if (AuthService::hasAccess($name)) {
                $visibleLinks[$name] = $path;
            }
        }

        return $visibleLinks;
    }

    /**
     * Returns all public links.
     */
    private static function publicLinks(): array
    {
        $base = $_ENV['APP_BASE_PATH'] ?? '';
        return [
            'Home'              => $base . '/home',
            'Listings'          => $base . '/listings',
            'About'             => $base . '/about',
            'Social Feed'       => $base . '/social-feed',
            'Contact'           => $base . '/contact',
        ];
    }

    /**
     * Returns the protected paths for route guarding. 🍊
     * Only paths that REQUIRE a login to see AT ALL should be here.
     */
    public static function getProtectedPaths(): array
    {
        $base = $_ENV['APP_BASE_PATH'] ?? '';

        // These are strictly private. 
        return [
            $base . '/dashboard',
            $base . '/saved',
            $base . '/chats',
            $base . '/profile',
            $base . '/users',
        ];
    }
}
