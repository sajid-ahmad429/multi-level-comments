@php
use Illuminate\Support\Str;
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-5xl font-extrabold mb-10 text-gray-900 text-center">All Posts</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
        @foreach($posts as $post)
            <a href="{{ route('posts.show', $post) }}" 
               class="group block bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden
                      transform hover:scale-[1.03] hover:shadow-2xl transition duration-300 ease-in-out">
                
                {{-- Using Picsum.photos random images with unique id seed --}}
                <div class="h-48 bg-gray-100 flex items-center justify-center overflow-hidden relative">
                    <img 
                        src="https://picsum.photos/seed/{{ $post->id }}/600/400" 
                        alt="{{ $post->title }}" 
                        loading="lazy"
                        class="object-cover w-full h-full transition-transform duration-500 ease-in-out opacity-0 group-hover:opacity-100 group-hover:scale-105"
                        onload="this.style.opacity='1'"
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
        @endforeach
    </div>
</div>
