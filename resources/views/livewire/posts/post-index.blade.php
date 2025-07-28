@php
    use Illuminate\Support\Str;
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <h1 class="text-5xl font-extrabold mb-10 text-gray-900 text-center">All Posts</h1>

    <!-- Sorting Buttons -->
    <div class="flex justify-center items-center mb-8 gap-4">
        <button wire:click="sortBy('title')" class="text-sm px-4 py-2 border rounded shadow-sm hover:bg-gray-100">
            Sort by Title
            @if($sortField === 'title')
                {{ $sortDirection === 'asc' ? '↑' : '↓' }}
            @endif
        </button>

        <button wire:click="sortBy('created_at')" class="text-sm px-4 py-2 border rounded shadow-sm hover:bg-gray-100">
            Sort by Date
            @if($sortField === 'created_at')
                {{ $sortDirection === 'asc' ? '↑' : '↓' }}
            @endif
        </button>
    </div>

    <!-- Post Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
        @forelse($posts as $post)
            <a href="{{ route('posts.show', $post) }}"
               class="group block bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden
                      transform hover:scale-[1.03] hover:shadow-2xl transition duration-300 ease-in-out">

                <div class="h-48 bg-gray-200 overflow-hidden relative">
                    {{-- Spinner placeholder --}}
                    <div class="absolute inset-0 flex items-center justify-center bg-gray-100 animate-pulse">
                        <svg class="w-6 h-6 text-gray-400 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                        </svg>
                    </div>

                    <img
                        src="https://picsum.photos/seed/{{ $post->id }}/600/400"
                        width="600" height="400"
                        alt="{{ $post->title }}"
                        loading="lazy"
                        class="object-cover w-full h-full transition-all duration-700 ease-in-out opacity-0 group-hover:opacity-100 group-hover:scale-105"
                        onload="this.style.opacity='1'; this.previousElementSibling?.remove();"
                    />
                </div>

                <div class="p-6 flex flex-col justify-between h-56">
                    <div>
                        <h2 class="text-xl font-bold mb-3 text-gray-900 group-hover:text-indigo-600 transition-colors duration-300 line-clamp-2">
                            {{ $post->title }}
                        </h2>

                        <p class="text-gray-700 text-sm leading-relaxed line-clamp-4">
                            {{ Str::limit(strip_tags($post->content), 140, '...') }}
                        </p>
                    </div>

                    <div class="mt-5 flex items-center justify-between text-xs text-gray-400 uppercase tracking-wide font-semibold">
                        <span>Published on {{ $post->created_at->format('M d, Y') }}</span>
                        <span class="text-indigo-600 group-hover:text-indigo-800 transition-colors duration-300 font-medium cursor-pointer">
                            Read More &rarr;
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-3 text-center text-gray-500">No posts found.</div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-10">
        {{ $posts->links() }}
    </div>
</div>
