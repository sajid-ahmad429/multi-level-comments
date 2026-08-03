<x-layouts.app :title="__('Dashboard')">
    @php
        $postsCount = \App\Models\Post::count();
        $commentsCount = \App\Models\Comment::count();
        $latestPosts = \App\Models\Post::query()->withCount('comments')->latest()->take(3)->get();
    @endphp

    <div class="flex h-full w-full flex-1 flex-col gap-8 pb-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        
        <!-- Clean Production Hero Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white border border-slate-200/80 rounded-3xl p-8 shadow-sm">
            <div class="space-y-1.5">
                <div class="inline-flex items-center gap-2 rounded-full bg-indigo-50 px-3.5 py-1 text-xs font-semibold text-indigo-700">
                    <span class="h-2 w-2 rounded-full bg-indigo-600 animate-pulse"></span> 
                    <span>Command Center</span>
                </div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">
                    Welcome back, Sajid 👋
                </h1>
                <p class="text-slate-500 text-sm">
                    Here is what's happening with your platform content and user engagement today.
                </p>
            </div>
            <div>
                <a href="{{ route('posts.index') }}" wire:navigate class="inline-flex items-center gap-2 rounded-2xl bg-indigo-600 px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-indigo-600/20 hover:bg-indigo-500 active:scale-[0.98] transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Create & Manage Posts
                </a>
            </div>
        </div>

        <!-- Metric Cards Grid -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            
            <!-- Total Posts -->
            <div class="rounded-3xl bg-white border border-slate-200/80 p-7 shadow-sm hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Posts</span>
                    <div class="h-10 w-10 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    </div>
                </div>
                <div class="mt-4 text-4xl font-extrabold text-slate-900">{{ $postsCount }}</div>
                <div class="mt-3 flex items-center gap-1.5 text-xs font-medium text-emerald-600">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                    <span>Live database feed</span>
                </div>
            </div>

            <!-- Total Comments -->
            <div class="rounded-3xl bg-white border border-slate-200/80 p-7 shadow-sm hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Comments</span>
                    <div class="h-10 w-10 rounded-2xl bg-sky-50 flex items-center justify-center text-sky-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                </div>
                <div class="mt-4 text-4xl font-extrabold text-slate-900">{{ $commentsCount }}</div>
                <div class="mt-3 text-xs font-medium text-slate-500">User engagement metrics</div>
            </div>

            <!-- Shortcut Hub -->
            <div class="rounded-3xl bg-slate-900 p-7 text-white shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-400">Quick Shortcut</span>
                    <div class="h-10 w-10 rounded-2xl bg-white/10 flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-lg font-bold">Content Hub</div>
                    <a href="{{ route('posts.index') }}" wire:navigate class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-indigo-300 hover:text-white transition-colors">
                        Explore all posts &rarr;
                    </a>
                </div>
            </div>
        </div>

        <!-- Lower Split Section -->
        <div class="grid gap-6 lg:grid-cols-[1.3fr_0.7fr]">
            
            <!-- Recent Articles -->
            <div class="rounded-3xl bg-white border border-slate-200/80 p-7 shadow-sm">
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Recent Articles</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Latest published posts on your platform</p>
                    </div>
                    <a href="{{ route('posts.index') }}" wire:navigate class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                        View All &rarr;
                    </a>
                </div>

                <div class="space-y-3.5">
                    @forelse ($latestPosts as $post)
                        <div class="group rounded-2xl border border-slate-100 bg-slate-50/50 p-4 hover:bg-white hover:border-indigo-200 hover:shadow-sm transition-all">
                            <div class="flex items-start justify-between gap-4">
                                <div class="space-y-1">
                                    <h3 class="font-bold text-sm text-slate-900 group-hover:text-indigo-600 transition-colors">
                                        {{ $post->title }}
                                    </h3>
                                    <p class="text-xs text-slate-500 leading-relaxed line-clamp-1">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($post->content), 90) }}
                                    </p>
                                </div>
                                <span class="shrink-0 rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                                    {{ $post->comments_count }} comments
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-200 p-8 text-center text-sm text-slate-400">
                            No posts available yet.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- System Diagnostics -->
            <div class="rounded-3xl bg-white border border-slate-200/80 p-7 shadow-sm flex flex-col justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">System Diagnostics</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Core component performance</p>

                    <div class="mt-5 space-y-3 text-sm">
                        <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                            <span class="text-slate-600 font-medium text-xs">Posts Module</span>
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Active
                            </span>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                            <span class="text-slate-600 font-medium text-xs">Comments API</span>
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Active
                            </span>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                            <span class="text-slate-600 font-medium text-xs">Livewire SPA Engine</span>
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full">
                                <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span> Ready
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 rounded-2xl bg-indigo-50/60 p-4 border border-indigo-100">
                    <p class="text-xs text-indigo-900 font-medium leading-relaxed">
                        ✨ Pro Tip: Keep your application dependencies up-to-date for maximum responsiveness and security.
                    </p>
                </div>
            </div>

        </div>
    </div>
</x-layouts.app>