<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 p-6']) }}>
    @if(isset($title))
        <h2 class="text-2xl font-semibold mb-3">{{ $title }}</h2>
    @endif
    {{ $slot }}
</div>
