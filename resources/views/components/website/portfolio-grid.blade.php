@props([
    'title' => 'Selected Work',
    'items' => [],
])

<section class="mx-auto max-w-7xl px-5 py-24 lg:px-8">
    <div class="mb-12 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <h2 class="text-4xl font-black tracking-[-.04em] sm:text-6xl">{{ $title }}</h2>
        <a href="{{ route('website.work') }}" class="text-sm font-black uppercase tracking-[.2em] text-amber-700">View work</a>
    </div>
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($items as $item)
            <article class="group overflow-hidden rounded-[2rem] bg-white shadow-sm">
                @if (data_get($item, 'video_url'))
                    <video src="{{ data_get($item, 'video_url') }}" class="aspect-[4/3] w-full object-cover" controls></video>
                @else
                    <img src="{{ data_get($item, 'image') }}" alt="{{ data_get($item, 'title') }}" class="aspect-[4/3] w-full object-cover transition duration-500 group-hover:scale-105">
                @endif
                <div class="p-6">
                    <h3 class="text-xl font-black">{{ data_get($item, 'title') }}</h3>
                    @if (data_get($item, 'body'))
                        <p class="mt-2 text-sm leading-6 text-slate-500">{{ data_get($item, 'body') }}</p>
                    @endif
                </div>
            </article>
        @endforeach
    </div>
</section>
