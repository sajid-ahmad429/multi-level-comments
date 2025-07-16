<div class="max-w-3xl mx-auto mt-10 bg-white shadow rounded-xl p-6">
    {{-- Replying Banner --}}
    @if ($replyingTo)
        <div class="mb-4 p-4 bg-indigo-50 rounded-md border-l-4 border-indigo-500 text-gray-700 text-sm">
            <strong>Replying to:</strong>
            {{ \Illuminate\Support\Str::limit(strip_tags($replyingTo), 100) }}
        </div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-6">
        <div class="relative">
            <textarea
                wire:model.defer="content"
                id="content"
                rows="4"
                maxlength="1000"
                class="peer h-32 w-full resize-none rounded-lg border border-gray-300 bg-transparent px-3 pt-6 pb-2 text-gray-900 placeholder-transparent shadow focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm"
                placeholder="Your message..."
            ></textarea>
            <label for="content" class="absolute left-3 top-3 text-sm text-gray-500 transition-all duration-200 peer-placeholder-shown:top-4 peer-placeholder-shown:text-gray-400 peer-focus:top-2 peer-focus:text-xs peer-focus:text-indigo-600">
                {{ $parentCommentId ? 'Write a reply...' : 'Add a comment...' }}
            </label>

            @error('content')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <p class="text-sm text-gray-400">
                Max 1000 characters
            </p>

            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-transparent bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                {{ $parentCommentId ? 'Reply' : 'Post Comment' }}
            </button>
        </div>
    </form>

    @if (session()->has('error'))
        <div class="mt-4 p-3 bg-red-100 text-sm text-red-700 rounded-md">
            {{ session('error') }}
        </div>
    @endif

    @if (session()->has('success'))
        <div class="mt-4 p-3 bg-green-100 text-green-800 rounded-md text-sm">
            {{ session('success') }}
        </div>
    @endif  
    
    {{-- Comments List --}}
</div>
