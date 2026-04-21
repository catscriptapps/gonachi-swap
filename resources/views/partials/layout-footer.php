<?php
// /resources/views/partials/layout-footer.php
?>

<footer class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 py-8 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">

            <div class="flex items-center space-x-3">
                <img class="h-8 w-8 grayscale opacity-80 dark:invert" src="<?= $assetBase ?>images/logo/favicon.png" alt="Favicon">
                <div class="flex flex-col md:flex-row md:items-center md:gap-x-2">
                    <span class="font-bold text-gray-900 dark:text-white tracking-tight"><?= htmlspecialchars($appName) ?></span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        &copy; <?= date('Y') ?> <span class="hidden md:inline">&bull;</span> All rights reserved.
                    </span>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <a href="<?= $baseUrl ?>" id="home-link" data-partial data-tooltip="Home" data-title="Home"
                    class="footer-nav-link text-sm font-medium text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 px-4 py-2 rounded-full transition-all duration-300">
                    Home
                </a>
                <a href="<?= $baseUrl ?>about" id="about-link" data-partial data-tooltip="About" data-title="About"
                    class="footer-nav-link text-sm font-medium text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 px-4 py-2 rounded-full transition-all duration-300">
                    About
                </a>
                <a href="<?= $baseUrl ?>contact" id="contact-link" data-partial data-tooltip="Contact" data-title="Contact"
                    class="footer-nav-link text-sm font-medium text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 px-4 py-2 rounded-full transition-all duration-300">
                    Contact
                </a>
                <a href="<?= $baseUrl ?>faqs" id="faqs-link" data-partial data-tooltip="FAQs" data-title="FAQs"
                    class="footer-nav-link text-sm font-medium text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 px-4 py-2 rounded-full transition-all duration-300">
                    FAQs
                </a>
            </div>

            <div class="hidden lg:block">
                <span class="text-xs font-medium uppercase tracking-widest text-gray-400 dark:text-gray-600">
                    A CatScript Application
                </span>
            </div>

        </div>
    </div>
</footer>