@php
use Illuminate\Support\Str;
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <h1 class="text-5xl font-extrabold mb-10 text-gray-900 text-center">All Posts</h1>

    @if (session()->has('success'))
        <div class="mb-6 rounded-lg bg-green-100 border border-green-200 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <!-- Create / Edit Form Card -->
<div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-slate-100 p-8 mb-12 transition-all">
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                {{ $editingPostId ? 'Edit Post' : 'Create New Post' }}
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">Fill in the fields below to publish or update your content.</p>
        </div>
        @if($editingPostId)
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                Editing Mode
            </span>
        @endif
    </div>

    <form wire:submit.prevent="{{ $editingPostId ? 'updatePost' : 'createPost' }}" class="space-y-6">
        <div class="grid grid-cols-1 gap-6">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Title</label>
                <input 
                    type="text" 
                    wire:model="title" 
                    class="w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-3 text-slate-900 shadow-sm focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 transition-all placeholder:text-slate-400" 
                    placeholder="What's on your mind?" 
                />
                @error('title') <span class="text-xs font-medium text-rose-600 mt-1.5 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Content</label>
                <textarea 
                    wire:model="content" 
                    rows="5" 
                    class="w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-3 text-slate-900 shadow-sm focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 transition-all placeholder:text-slate-400 resize-none" 
                    placeholder="Write your post content here..."></textarea>
                @error('content') <span class="text-xs font-medium text-rose-600 mt-1.5 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-600/20 hover:bg-indigo-500 active:scale-[0.98] transition-all">
                {{ $editingPostId ? 'Update Post' : 'Publish Post' }}
            </button>

            @if($editingPostId)
                <button type="button" wire:click="cancelEdit" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 active:scale-[0.98] transition-all">
                    Cancel
                </button>
            @endif
        </div>
    </form>
</div>

    <div class="flex justify-center items-center mb-8 gap-4">
        <input
            type="text"
            wire:model.live.debounce.300ms="searchTerm"
            placeholder="Search posts..."
            class="w-full max-w-md px-4 h-10 border rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />

        <button wire:click="sortBy('title')" class="text-sm px-4 h-10 border rounded shadow-sm hover:bg-gray-100">
            Sort by Title
            @if($sortField === 'title')
            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
            @endif
        </button>

        <button wire:click="sortBy('created_at')" class="text-sm px-4 h-10 border rounded shadow-sm hover:bg-gray-100">
            Sort by Date
            @if($sortField === 'created_at')
            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
            @endif
        </button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
        @forelse($posts as $post)
        <div class="group bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden transform hover:scale-[1.03] hover:shadow-2xl transition duration-300 ease-in-out">
            <div class="h-48 bg-gray-200 overflow-hidden relative">
                <div class="absolute inset-0 flex items-center justify-center bg-gray-100 animate-pulse">
                    <svg class="w-6 h-6 text-gray-400 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                    </svg>
                </div>

                <img
                    src="https://picsum.photos/seed/{{ $post->id }}/600/400"
                    width="600" height="400"
                    alt="{{ $post->title }}"
                    loading="lazy"
                    class="object-cover w-full h-full transition-all duration-700 ease-in-out opacity-0 group-hover:opacity-100 group-hover:scale-105"
                    onload="this.style.opacity='1'; this.previousElementSibling?.remove();" />
            </div>

            <div class="p-6 flex flex-col justify-between h-72">
                <div>
                    <h2 class="text-xl font-bold mb-3 text-gray-900 group-hover:text-indigo-600 transition-colors duration-300 line-clamp-2">
                        {{ $post->title }}
                    </h2>

                    <p class="text-gray-700 text-sm leading-relaxed line-clamp-4">
                        {{ Str::limit(strip_tags($post->content), 140, '...') }}
                    </p>
                </div>

                <div class="mt-5 flex items-center justify-between text-xs text-gray-400 uppercase tracking-wide font-semibold gap-2">
                    <span>Published on {{ $post->created_at->format('M d, Y') }}</span>
                    <div class="flex items-center gap-2 text-indigo-600">
                        <a href="{{ route('posts.show', $post) }}" class="font-medium hover:text-indigo-800">Read More &rarr;</a>
                        <button type="button" wire:click="editPost({{ $post->id }})" class="font-medium hover:text-indigo-800">Edit</button>
                        <button type="button" onclick="confirmDelete({{ $post->id }})" class="font-medium hover:text-red-600">Delete</button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center text-gray-500">No posts found.</div>
        @endforelse
    </div>

    <div class="mt-10">
        {{ $posts->links() }}
    </div>
</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(postId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5', // Indigo color matching your theme
            cancelButtonColor: '#ef4444',  // Red color
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-3xl shadow-xl border border-slate-100',
                confirmButton: 'rounded-xl px-5 py-2.5 text-sm font-semibold',
                cancelButton: 'rounded-xl px-5 py-2.5 text-sm font-semibold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Livewire component method call
                @this.call('deletePost', postId);
            }
        });
    }

    // Optional: Success notification after deletion
    window.addEventListener('post-deleted', event => {
        Swal.fire({
            title: 'Deleted!',
            text: 'Your post has been deleted successfully.',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
    });
</script>