<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 bg-white rounded-2xl shadow-2xl">

    {{-- Title --}}
    <h1 class="text-4xl font-bold mb-6 text-gray-900">{{ $post->title }}</h1>

    {{-- Content --}}
    <article class="prose prose-lg max-w-none mb-8 text-gray-800">
        {!! $post->content !!}
    </article>

    {{-- Published Date --}}
    <div class="text-sm text-gray-500 mb-10">
        Published on {{ $post->created_at->format('F j, Y') }}
    </div>

    {{-- Back Link --}}
    <a href="{{ route('posts.index') }}"
        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition mb-12">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 -ml-1" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        Back to all posts
    </a>

    <hr class="mb-12 border-gray-200">

    {{-- Comments --}}
    <section id="comments">
        <h2 class="text-3xl font-semibold text-gray-900 mb-8">Comments ({{ $comments->count() }})</h2>

        {{-- Livewire comments list --}}
        @livewire('comments.comments-list', ['post' => $post])

        {{-- Empty state handled inside the comments-list component --}}
    </section>

    {{-- Add Comment --}}
    <section class="mt-16 bg-gray-50 border border-gray-200 rounded-xl shadow p-8">
        <h3 class="flex items-center text-2xl font-semibold text-gray-800 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500 mr-2" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add a Comment
        </h3>
        <p class="text-gray-500 mb-6">
            Share your thoughts about this post. Your feedback helps others.
        </p>

        @livewire('comments.comment-form', ['postId' => $post->id])
    </section>

</div>
