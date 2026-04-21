<?php
// /resources/views/pages/groups.php
$pageIcon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>';
?>

<div id="community-groups-page" class="min-h-screen bg-gray-50 dark:bg-gray-950 font-sans pb-12 transition-colors duration-300">
    <div class="max-w-7xl mx-auto pt-8 px-4 sm:px-6 lg:px-8">

        <div class="grid lg:grid-cols-12 gap-6 items-stretch">

            <div class="lg:col-span-8 relative overflow-hidden rounded-[2.5rem] bg-white dark:bg-gray-900 shadow-xl border border-gray-200/60 dark:border-white/5 p-8 lg:p-14" data-aos="fade-right">
                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary-500/10 border border-primary-500/20 text-primary-600 dark:text-primary-400 text-[9px] font-black uppercase tracking-widest mb-6">
                        <span class="flex h-2 w-2 rounded-full bg-primary-500 animate-ping"></span>
                        Social Infrastructure Active
                    </div>

                    <h1 class="text-5xl lg:text-7xl font-black tracking-tighter text-secondary-900 dark:text-white leading-[0.9] mb-6">
                        Your Network, <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-secondary-500">Your Rules.</span>
                    </h1>

                    <p class="max-w-xl text-lg text-gray-500 dark:text-gray-400 mb-10 leading-relaxed font-medium">
                        Create unlimited secure spaces for tenants, contractors, or investors. Share rich media and insights instantly.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-center">
                        <button class="w-full sm:w-auto px-8 py-4 bg-primary-600 hover:bg-primary-500 text-white font-black rounded-xl shadow-lg shadow-primary-500/30 transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2 text-sm">
                            Create New Group
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>

                        <button class="w-full sm:w-auto px-6 py-4 rounded-xl bg-secondary-900 dark:bg-white/5 text-white text-sm font-black border border-transparent dark:border-white/10 backdrop-blur-md hover:bg-secondary-800 transition-all flex items-center justify-center">
                            Browse My Groups
                        </button>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4 flex flex-col gap-4" data-aos="fade-left">

                <div class="rounded-3xl bg-white dark:bg-secondary-900 p-5 border border-gray-200 dark:border-white/5 shadow-xl group">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="h-10 w-10 rounded-xl bg-primary-500 flex items-center justify-center text-white font-black shadow-lg">TC</div>
                        <div class="min-w-0">
                            <h4 class="text-[11px] font-black dark:text-white uppercase truncate">My Tenants (Ontario)</h4>
                            <span class="text-[9px] text-primary-500 font-bold">12 Participants • Active</span>
                        </div>
                    </div>
                    <div class="aspect-video bg-gray-100 dark:bg-gray-800 rounded-2xl flex items-center justify-center relative overflow-hidden group-hover:shadow-inner transition-all">
                        <svg class="w-8 h-8 text-red-500 z-10" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z" />
                        </svg>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                    </div>
                </div>

                <div class="flex-1 rounded-3xl bg-secondary-950 p-6 border border-white/5 shadow-2xl">
                    <span class="text-primary-400 text-[8px] font-black uppercase tracking-[0.3em] block mb-4">Segment Your World</span>
                    <div class="grid grid-cols-2 gap-2">
                        <?php foreach (['Tenants', 'Contractors', 'Agents', 'Managers'] as $cat): ?>
                            <div class="px-3 py-3 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 transition-all cursor-pointer group">
                                <span class="text-[9px] font-black text-gray-400 group-hover:text-white uppercase"><?= $cat ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-6 pt-6 border-t border-white/5 flex items-center justify-between">
                        <div class="flex -space-x-2">
                            <div class="w-6 h-6 rounded-full bg-primary-500 border border-secondary-950"></div>
                            <div class="w-6 h-6 rounded-full bg-secondary-500 border border-secondary-950"></div>
                            <div class="w-6 h-6 rounded-full bg-gray-600 border border-secondary-950 flex items-center justify-center text-[7px] text-white font-black">+4k</div>
                        </div>
                        <span class="text-[9px] text-gray-500 font-bold italic">Global Sync</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 grid md:grid-cols-2 lg:grid-cols-4 gap-4" data-aos="fade-up">
            <?php
            $tools = [
                ['Unlimited Members', 'M12 4.354a4 4 0 110 5.292', 'primary'],
                ['Rich Media', 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z', 'secondary'],
                ['Permissions', 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z', 'primary'],
                ['Ad-Integration', 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z', 'secondary']
            ];
            foreach ($tools as $t): ?>
                <div class="group p-5 rounded-[2rem] bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 flex items-center gap-4 hover:shadow-lg transition-all">
                    <div class="h-10 w-10 shrink-0 rounded-2xl bg-<?= $t[2] ?>-500/10 flex items-center justify-center text-<?= $t[2] ?>-500 group-hover:bg-<?= $t[2] ?>-500 group-hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $t[1] ?>" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <span class="block text-xs font-black text-secondary-900 dark:text-white uppercase truncate"><?= $t[0] ?></span>
                        <span class="text-[10px] text-gray-500 font-medium">Infrastructure Active</span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>