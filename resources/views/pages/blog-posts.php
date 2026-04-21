<?php
// /resources/views/pages/blog-posts.php

$title = 'Industry Insights | Gonachi News';

// Page Icon: Newspaper/Article
$pageIcon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2zM7 8h4m-4 4h8m-8 4h8" /></svg>';
?>

<div id="blog-posts-page" class="min-h-screen bg-gray-50 dark:bg-gray-950 font-sans pb-20 transition-colors duration-300 overflow-hidden">

    <div class="max-w-7xl mx-auto pt-12 px-4 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-[3rem] bg-secondary-900 dark:bg-gray-900/50 p-8 lg:p-20 shadow-2xl border border-white/5" data-aos="zoom-in">

            <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-primary-500/20 to-transparent"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-secondary-500/10 rounded-full blur-[100px]"></div>

            <div class="relative flex flex-col lg:flex-row items-center gap-16">
                <div class="flex-1 text-center lg:text-left" data-aos="fade-right" data-aos-delay="200">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-500 text-white text-[10px] font-black uppercase tracking-[0.3em] mb-8">
                        Editor's Choice
                    </div>

                    <h1 class="text-5xl lg:text-7xl font-black tracking-tighter text-white leading-[0.9] mb-8">
                        The Future of <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-primary-200">Real Estate</span>
                    </h1>

                    <p class="text-xl text-gray-300 mb-10 leading-relaxed font-medium max-w-xl">
                        Explore deep dives into real estate market trends, mortgage strategies, and property management secrets curated by the Gonachi team.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-center justify-center lg:justify-start">
                        <button class="w-full sm:w-auto px-8 py-4 bg-white text-secondary-900 font-black rounded-2xl hover:bg-primary-400 hover:text-white transition-all transform hover:-translate-y-1 flex items-center justify-center">
                            Read Latest Entry
                        </button>

                        <div class="flex items-center justify-center gap-4 px-6 py-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md w-full sm:w-auto">
                            <div class="flex -space-x-2">
                                <div class="w-8 h-8 rounded-full border-2 border-secondary-900 bg-primary-500"></div>
                                <div class="w-8 h-8 rounded-full border-2 border-secondary-900 bg-secondary-400"></div>
                            </div>
                            <span class="text-xs font-bold text-gray-400 whitespace-nowrap">5k+ Weekly Readers</span>
                        </div>
                    </div>
                </div>

                <div class="relative w-full max-w-md hidden lg:block" data-aos="fade-left" data-aos-delay="400">
                    <div class="relative bg-white dark:bg-gray-800 rounded-[2.5rem] p-4 shadow-3xl rotate-3 hover:rotate-0 transition-transform duration-700">
                        <div class="aspect-[4/3] bg-gray-100 dark:bg-gray-700 rounded-[2rem] mb-6 overflow-hidden relative group">
                            <div class="absolute inset-0 bg-gradient-to-t from-secondary-900/80 to-transparent flex items-end p-8">
                                <span class="text-white font-black text-2xl leading-tight">Navigating the 2026 Housing Market</span>
                            </div>
                        </div>
                        <div class="px-4 pb-4 space-y-3">
                            <div class="h-2 w-full bg-gray-100 dark:bg-gray-700 rounded-full"></div>
                            <div class="h-2 w-2/3 bg-gray-100 dark:bg-gray-700 rounded-full"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto mt-20 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 border-b border-gray-200 dark:border-white/10 pb-10">
            <div class="flex flex-wrap gap-3">
                <?php
                $categories = ['All', 'Market Trends', 'Legal Advice', 'Investment', 'Tech Updates'];
                foreach ($categories as $cat): ?>
                    <button class="px-6 py-2 rounded-full border border-gray-200 dark:border-white/10 text-sm font-black text-gray-600 dark:text-gray-400 hover:border-primary-500 hover:text-primary-500 transition-all">
                        <?= $cat ?>
                    </button>
                <?php endforeach; ?>
            </div>
            <div class="relative max-w-xs w-full">
                <input type="text" placeholder="Search insights..." class="w-full pl-12 pr-4 py-3 rounded-2xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 focus:border-primary-500 outline-none transition-all">
                <svg class="absolute left-4 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2.5" />
                </svg>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto mt-16 px-4 sm:px-6 lg:px-8 grid md:grid-cols-3 gap-10">
        <?php for ($i = 1; $i <= 3; $i++): ?>
            <article class="group cursor-pointer" data-aos="fade-up" data-aos-delay="<?= $i * 100 ?>">
                <div class="relative aspect-video rounded-[2rem] bg-gray-200 dark:bg-white/5 mb-6 overflow-hidden shadow-xl">
                    <div class="absolute inset-0 bg-gradient-to-tr from-primary-500/20 to-secondary-500/20 group-hover:opacity-0 transition-opacity"></div>
                    <div class="absolute top-4 right-4 px-3 py-1 bg-white/90 backdrop-blur rounded-lg text-[10px] font-black uppercase text-secondary-900">5 min read</div>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center gap-2 text-primary-500 text-[10px] font-black uppercase tracking-widest">
                        <span>Investment</span>
                        <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                        <span class="text-gray-400">Feb 28, 2026</span>
                    </div>
                    <h3 class="text-2xl font-black text-secondary-900 dark:text-white group-hover:text-primary-500 transition-colors leading-tight">
                        Maximizing ROI on Multi-Family Properties
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed line-clamp-2">
                        Discover how seasoned investors are shifting their portfolios to accommodate the rising demand in urban sectors...
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        <div class="w-8 h-8 rounded-full bg-secondary-900"></div>
                        <span class="text-xs font-black dark:text-white uppercase tracking-tighter">Gonachi Editorial</span>
                    </div>
                </div>
            </article>
        <?php endfor; ?>
    </div>
</div>