@php
    $sections = old('sections') ? json_decode(old('sections'), true) : ($page->sections ?? []);
    $seo = old('seo') ? json_decode(old('seo'), true) : ($page->seo ?? []);
    $pageType = old('page_type', data_get($sections, 'type', $page->key ?: 'content'));
    $pageType = in_array($pageType, ['home', 'content', 'services', 'portfolio', 'team', 'clients', 'contact'], true) ? $pageType : 'content';

    $hero = data_get($sections, 'hero', []);
    $stats = data_get($sections, 'stats', []);
    $intro = data_get($sections, 'intro', []);
    $contentItems = $pageType === 'content' ? data_get($sections, 'items', []) : [];
    $servicesBlock = data_get($sections, 'services', []);
    $services = data_get($servicesBlock, 'items', $pageType === 'services' ? data_get($sections, 'items', []) : []);
    $portfolioBlock = data_get($sections, 'portfolio', []);
    $portfolio = data_get($portfolioBlock, 'items', $pageType === 'portfolio' ? data_get($sections, 'items', []) : []);
    $team = $pageType === 'team' ? data_get($sections, 'items', []) : [];
    $clients = $pageType === 'clients' ? data_get($sections, 'items', []) : [];
    $contacts = data_get($sections, 'contacts', []);
    $locations = data_get($sections, 'locations', []);
    $cta = data_get($sections, 'cta', []);

    $padRows = function (array $rows, int $count): array {
        while (count($rows) < $count) {
            $rows[] = [];
        }

        return $rows;
    };

    $stats = $padRows($stats, 4);
    $contentItems = $padRows($contentItems, 6);
    $services = $padRows($services, 8);
    $portfolio = $padRows($portfolio, 12);
    $team = $padRows($team, 10);
    $clients = $padRows($clients, 16);
    $contacts = $padRows($contacts, 6);
    $locations = $padRows($locations, 4);

    $inputClass = 'w-full rounded-xl border-slate-300 text-sm';
    $labelClass = 'mb-2 block text-sm font-semibold text-slate-700';
@endphp

<x-app-layout>
    <x-slot name="header">{{ $page->exists ? 'Edit Website Page' : 'New Website Page' }}</x-slot>

    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5 text-sm text-red-700">
            <div class="font-bold">Please check the highlighted fields.</div>
            <ul class="mt-2 list-disc ps-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ $page->exists ? route('admin.cms.update', $page) : route('admin.cms.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if($page->exists) @method('PUT') @endif

        <section class="rounded-2xl bg-white p-6 shadow">
            <div class="mb-5 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-xl font-bold">Page Settings</h2>
                    <p class="text-sm text-slate-500">These pages appear on the public website and can be opened from Website Pages.</p>
                </div>
                @if ($page->exists)
                    <a href="{{ $page->key === 'home' ? route('website.home') : url($page->slug) }}" target="_blank" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold">Open public page</a>
                @endif
            </div>

            <div class="grid gap-4 md:grid-cols-4">
                <label>
                    <span class="{{ $labelClass }}">Key</span>
                    <input name="key" value="{{ old('key', $page->key) }}" class="{{ $inputClass }}" required>
                </label>
                <label>
                    <span class="{{ $labelClass }}">Title</span>
                    <input name="title" value="{{ old('title', $page->title) }}" class="{{ $inputClass }}" required>
                </label>
                <label>
                    <span class="{{ $labelClass }}">Slug</span>
                    <input name="slug" value="{{ old('slug', $page->slug) }}" class="{{ $inputClass }}" required>
                </label>
                <label>
                    <span class="{{ $labelClass }}">Page Type</span>
                    <select name="page_type" class="{{ $inputClass }}">
                        @foreach (['home' => 'Home', 'content' => 'Content/About', 'services' => 'Services', 'portfolio' => 'Work/Portfolio', 'team' => 'Team', 'clients' => 'Clients', 'contact' => 'Contact'] as $value => $label)
                            <option value="{{ $value }}" @selected($pageType === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <div class="mt-5 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
                هذه الشاشة تعرض فقط أقسام نوع الصفحة المختار حتى لا تختلط عليك الحقول. إذا غيرت Page Type احفظ الصفحة ثم افتحها مرة ثانية لترى حقول النوع الجديد.
            </div>

            <label class="mt-5 flex items-center gap-2">
                <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $page->exists ? $page->is_published : true))>
                <span class="font-semibold">Published</span>
            </label>
        </section>

        <section class="rounded-2xl bg-white p-6 shadow">
            <h2 class="mb-5 text-xl font-bold">SEO</h2>
            <div class="grid gap-4 md:grid-cols-2">
                <label>
                    <span class="{{ $labelClass }}">SEO Title</span>
                    <input name="seo_title" value="{{ old('seo_title', data_get($seo, 'title')) }}" class="{{ $inputClass }}">
                </label>
                <label>
                    <span class="{{ $labelClass }}">SEO Description</span>
                    <input name="seo_description" value="{{ old('seo_description', data_get($seo, 'description')) }}" class="{{ $inputClass }}">
                </label>
            </div>
        </section>

        <section class="rounded-2xl bg-white p-6 shadow">
            <h2 class="mb-5 text-xl font-bold">Hero / Top Section</h2>
            <div class="grid gap-4 md:grid-cols-2">
                <label>
                    <span class="{{ $labelClass }}">Small heading</span>
                    <input name="hero_eyebrow" value="{{ old('hero_eyebrow', data_get($hero, 'eyebrow')) }}" class="{{ $inputClass }}">
                </label>
                <label>
                    <span class="{{ $labelClass }}">Main title</span>
                    <input name="hero_title" value="{{ old('hero_title', data_get($hero, 'title')) }}" class="{{ $inputClass }}">
                </label>
                <label class="md:col-span-2">
                    <span class="{{ $labelClass }}">Intro text</span>
                    <textarea name="hero_body" rows="4" class="{{ $inputClass }}">{{ old('hero_body', data_get($hero, 'body')) }}</textarea>
                </label>
                <label>
                    <span class="{{ $labelClass }}">Current image URL</span>
                    <input name="hero_image" value="{{ old('hero_image', data_get($hero, 'image')) }}" class="{{ $inputClass }}">
                </label>
                <label>
                    <span class="{{ $labelClass }}">Upload new image</span>
                    <input type="file" name="hero_image_upload" accept="image/*" class="{{ $inputClass }} bg-white p-2">
                </label>
                <label>
                    <span class="{{ $labelClass }}">Video URL</span>
                    <input name="hero_video_url" value="{{ old('hero_video_url', data_get($hero, 'video_url')) }}" class="{{ $inputClass }}" placeholder="YouTube/Vimeo or /storage/video.mp4">
                </label>
                <label>
                    <span class="{{ $labelClass }}">Upload video</span>
                    <input type="file" name="hero_video_upload" accept="video/mp4,video/webm,video/quicktime" class="{{ $inputClass }} bg-white p-2">
                </label>
                <div class="grid gap-4 md:grid-cols-2">
                    <label>
                        <span class="{{ $labelClass }}">Primary button</span>
                        <input name="primary_label" value="{{ old('primary_label', data_get($hero, 'primary_label')) }}" class="{{ $inputClass }}">
                    </label>
                    <label>
                        <span class="{{ $labelClass }}">Primary URL</span>
                        <input name="primary_url" value="{{ old('primary_url', data_get($hero, 'primary_url')) }}" class="{{ $inputClass }}">
                    </label>
                </div>
            </div>
        </section>

        @if (in_array($pageType, ['home', 'content'], true))
        <section class="rounded-2xl bg-white p-6 shadow">
            <h2 class="mb-5 text-xl font-bold">Home/About Intro & Stats</h2>
            <div class="grid gap-4 md:grid-cols-3">
                <label>
                    <span class="{{ $labelClass }}">Intro eyebrow</span>
                    <input name="intro_eyebrow" value="{{ old('intro_eyebrow', data_get($intro, 'eyebrow')) }}" class="{{ $inputClass }}">
                </label>
                <label class="md:col-span-2">
                    <span class="{{ $labelClass }}">Intro title</span>
                    <input name="intro_title" value="{{ old('intro_title', data_get($intro, 'title')) }}" class="{{ $inputClass }}">
                </label>
                <label class="md:col-span-3">
                    <span class="{{ $labelClass }}">Intro body</span>
                    <textarea name="intro_body" rows="3" class="{{ $inputClass }}">{{ old('intro_body', data_get($intro, 'body')) }}</textarea>
                </label>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-4">
                @foreach ($stats as $index => $stat)
                    <div class="rounded-xl border border-slate-200 p-4">
                        <div class="text-sm font-bold text-slate-500">Stat {{ $index + 1 }}</div>
                        <input name="stats[{{ $index }}][value]" value="{{ data_get($stat, 'value') }}" placeholder="41+" class="{{ $inputClass }} mt-3">
                        <input name="stats[{{ $index }}][label]" value="{{ data_get($stat, 'label') }}" placeholder="Years of experience" class="{{ $inputClass }} mt-3">
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        @if ($pageType === 'content')
        <section class="rounded-2xl bg-white p-6 shadow">
            <h2 class="mb-5 text-xl font-bold">Content Blocks</h2>
            <p class="mb-5 text-sm text-slate-500">Use this for About page cards or any regular content page sections.</p>
            <div class="grid gap-4">
                @foreach ($contentItems as $index => $item)
                    <div class="rounded-xl border border-slate-200 p-4">
                        <div class="mb-3 text-sm font-bold text-slate-500">Block {{ $index + 1 }}</div>
                        <input name="content_items[{{ $index }}][title]" value="{{ data_get($item, 'title') }}" placeholder="Block title" class="{{ $inputClass }}">
                        <textarea name="content_items[{{ $index }}][body]" rows="3" placeholder="Block text" class="{{ $inputClass }} mt-3">{{ data_get($item, 'body') }}</textarea>
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        @if (in_array($pageType, ['home', 'services'], true))
        <section class="rounded-2xl bg-white p-6 shadow">
            <h2 class="mb-5 text-xl font-bold">Services</h2>
            @if ($pageType === 'home')
            <div class="grid gap-4 md:grid-cols-2">
                <label>
                    <span class="{{ $labelClass }}">Services block title</span>
                    <input name="services_title" value="{{ old('services_title', data_get($servicesBlock, 'title')) }}" class="{{ $inputClass }}">
                </label>
                <label>
                    <span class="{{ $labelClass }}">Services block text</span>
                    <input name="services_body" value="{{ old('services_body', data_get($servicesBlock, 'body')) }}" class="{{ $inputClass }}">
                </label>
            </div>
            @endif
            <div class="mt-6 grid gap-4">
                @foreach ($services as $index => $service)
                    <div class="rounded-xl border border-slate-200 p-4">
                        <div class="mb-3 text-sm font-bold text-slate-500">Service {{ $index + 1 }}</div>
                        <div class="grid gap-3 md:grid-cols-2">
                            <input name="services[{{ $index }}][title]" value="{{ data_get($service, 'title') }}" placeholder="Service title" class="{{ $inputClass }}">
                            <input name="services[{{ $index }}][video_url]" value="{{ data_get($service, 'video_url') }}" placeholder="Video URL" class="{{ $inputClass }}">
                            <textarea name="services[{{ $index }}][body]" rows="2" placeholder="Service description" class="{{ $inputClass }} md:col-span-2">{{ data_get($service, 'body') }}</textarea>
                            <input name="services[{{ $index }}][image]" value="{{ data_get($service, 'image') }}" placeholder="Image URL" class="{{ $inputClass }}">
                            <input type="file" name="services[{{ $index }}][image_upload]" accept="image/*" class="{{ $inputClass }} bg-white p-2">
                            <input type="file" name="services[{{ $index }}][video_upload]" accept="video/mp4,video/webm,video/quicktime" class="{{ $inputClass }} bg-white p-2 md:col-span-2">
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        @if (in_array($pageType, ['home', 'portfolio'], true))
        <section class="rounded-2xl bg-white p-6 shadow">
            <h2 class="mb-5 text-xl font-bold">Projects / Work / Portfolio</h2>
            @if ($pageType === 'home')
            <label>
                <span class="{{ $labelClass }}">Portfolio block title</span>
                <input name="portfolio_title" value="{{ old('portfolio_title', data_get($portfolioBlock, 'title')) }}" class="{{ $inputClass }}">
            </label>
            @endif
            <div class="mt-6 grid gap-4">
                @foreach ($portfolio as $index => $item)
                    <div class="rounded-xl border border-slate-200 p-4">
                        <div class="mb-3 text-sm font-bold text-slate-500">Project {{ $index + 1 }}</div>
                        <div class="grid gap-3 md:grid-cols-2">
                            <input name="portfolio[{{ $index }}][title]" value="{{ data_get($item, 'title') }}" placeholder="Project title" class="{{ $inputClass }}">
                            <input name="portfolio[{{ $index }}][video_url]" value="{{ data_get($item, 'video_url') }}" placeholder="Video URL" class="{{ $inputClass }}">
                            <textarea name="portfolio[{{ $index }}][body]" rows="2" placeholder="Project description" class="{{ $inputClass }} md:col-span-2">{{ data_get($item, 'body') }}</textarea>
                            <input name="portfolio[{{ $index }}][image]" value="{{ data_get($item, 'image') }}" placeholder="Image URL" class="{{ $inputClass }}">
                            <input type="file" name="portfolio[{{ $index }}][image_upload]" accept="image/*" class="{{ $inputClass }} bg-white p-2">
                            <input type="file" name="portfolio[{{ $index }}][video_upload]" accept="video/mp4,video/webm,video/quicktime" class="{{ $inputClass }} bg-white p-2 md:col-span-2">
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        @if ($pageType === 'team')
        <section class="rounded-2xl bg-white p-6 shadow">
            <h2 class="mb-5 text-xl font-bold">Team Members</h2>
            <div class="grid gap-4">
                @foreach ($team as $index => $member)
                    <div class="rounded-xl border border-slate-200 p-4">
                        <div class="mb-3 text-sm font-bold text-slate-500">Member {{ $index + 1 }}</div>
                        <div class="grid gap-3 md:grid-cols-2">
                            <input name="team[{{ $index }}][name]" value="{{ data_get($member, 'name') }}" placeholder="Name" class="{{ $inputClass }}">
                            <input name="team[{{ $index }}][role]" value="{{ data_get($member, 'role') }}" placeholder="Role" class="{{ $inputClass }}">
                            <input name="team[{{ $index }}][email]" value="{{ data_get($member, 'email') }}" placeholder="Email" class="{{ $inputClass }}">
                            <input name="team[{{ $index }}][video_url]" value="{{ data_get($member, 'video_url') }}" placeholder="Video URL" class="{{ $inputClass }}">
                            <input name="team[{{ $index }}][image]" value="{{ data_get($member, 'image') }}" placeholder="Image URL" class="{{ $inputClass }}">
                            <input type="file" name="team[{{ $index }}][image_upload]" accept="image/*" class="{{ $inputClass }} bg-white p-2">
                            <input type="file" name="team[{{ $index }}][video_upload]" accept="video/mp4,video/webm,video/quicktime" class="{{ $inputClass }} bg-white p-2 md:col-span-2">
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        @if ($pageType === 'clients')
        <section class="rounded-2xl bg-white p-6 shadow">
            <h2 class="mb-5 text-xl font-bold">Client Logos</h2>
            <div class="grid gap-4 md:grid-cols-2">
                @foreach ($clients as $index => $client)
                    <div class="rounded-xl border border-slate-200 p-4">
                        <div class="mb-3 text-sm font-bold text-slate-500">Client {{ $index + 1 }}</div>
                        <input name="clients[{{ $index }}][name]" value="{{ data_get($client, 'name') }}" placeholder="Client name" class="{{ $inputClass }}">
                        <input name="clients[{{ $index }}][image]" value="{{ data_get($client, 'image') }}" placeholder="Logo URL" class="{{ $inputClass }} mt-3">
                        <input type="file" name="clients[{{ $index }}][image_upload]" accept="image/*" class="{{ $inputClass }} mt-3 bg-white p-2">
                        <input name="clients[{{ $index }}][video_url]" value="{{ data_get($client, 'video_url') }}" placeholder="Video URL" class="{{ $inputClass }} mt-3">
                        <input type="file" name="clients[{{ $index }}][video_upload]" accept="video/mp4,video/webm,video/quicktime" class="{{ $inputClass }} mt-3 bg-white p-2">
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        @if ($pageType === 'contact')
        <section class="rounded-2xl bg-white p-6 shadow">
            <h2 class="mb-5 text-xl font-bold">Contact Page</h2>
            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-4">
                    @foreach ($contacts as $index => $contact)
                        <div class="rounded-xl border border-slate-200 p-4">
                            <div class="mb-3 text-sm font-bold text-slate-500">Contact {{ $index + 1 }}</div>
                            <input name="contacts[{{ $index }}][label]" value="{{ data_get($contact, 'label') }}" placeholder="Label" class="{{ $inputClass }}">
                            <input name="contacts[{{ $index }}][value]" value="{{ data_get($contact, 'value') }}" placeholder="Value" class="{{ $inputClass }} mt-3">
                            <input name="contacts[{{ $index }}][url]" value="{{ data_get($contact, 'url') }}" placeholder="Link" class="{{ $inputClass }} mt-3">
                        </div>
                    @endforeach
                </div>
                <div class="space-y-4">
                    @foreach ($locations as $index => $location)
                        <div class="rounded-xl border border-slate-200 p-4">
                            <div class="mb-3 text-sm font-bold text-slate-500">Location {{ $index + 1 }}</div>
                            <input name="locations[{{ $index }}][city]" value="{{ data_get($location, 'city') }}" placeholder="City" class="{{ $inputClass }}">
                            <textarea name="locations[{{ $index }}][address]" rows="3" placeholder="Address" class="{{ $inputClass }} mt-3">{{ data_get($location, 'address') }}</textarea>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        @if (in_array($pageType, ['home', 'contact'], true))
        <section class="rounded-2xl bg-white p-6 shadow">
            <h2 class="mb-5 text-xl font-bold">Call To Action</h2>
            <div class="grid gap-4 md:grid-cols-2">
                <input name="cta_title" value="{{ old('cta_title', data_get($cta, 'title')) }}" placeholder="CTA title" class="{{ $inputClass }}">
                <input name="cta_label" value="{{ old('cta_label', data_get($cta, 'label')) }}" placeholder="Button label" class="{{ $inputClass }}">
                <textarea name="cta_body" rows="3" placeholder="CTA text" class="{{ $inputClass }}">{{ old('cta_body', data_get($cta, 'body')) }}</textarea>
                <input name="cta_url" value="{{ old('cta_url', data_get($cta, 'url')) }}" placeholder="Button URL" class="{{ $inputClass }}">
            </div>
        </section>
        @endif

        <details class="rounded-2xl bg-white p-6 shadow">
            <summary class="cursor-pointer text-xl font-bold">Advanced JSON editor</summary>
            <p class="mt-2 text-sm text-slate-500">Use only when you need direct JSON editing. If filled, it overrides the structured fields above.</p>
            <label class="mt-5 block">
                <span class="{{ $labelClass }}">Sections JSON</span>
                <textarea name="sections" rows="12" class="w-full rounded-xl border-slate-300 font-mono text-xs">{{ old('sections') }}</textarea>
            </label>
            <label class="mt-5 block">
                <span class="{{ $labelClass }}">SEO JSON</span>
                <textarea name="seo" rows="5" class="w-full rounded-xl border-slate-300 font-mono text-xs">{{ old('seo') }}</textarea>
            </label>
        </details>

        <div class="sticky bottom-0 flex justify-end gap-3 border-t border-slate-200 bg-slate-100/95 py-4 backdrop-blur">
            <a href="{{ route('admin.cms.index') }}" class="rounded-xl border border-slate-300 px-5 py-3 font-semibold">Cancel</a>
            <button class="rounded-xl bg-slate-950 px-6 py-3 font-semibold text-white">Save page</button>
        </div>
    </form>
</x-app-layout>
