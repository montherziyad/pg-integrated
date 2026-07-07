<x-app-layout>
    <x-slot name="header">Website Pages</x-slot>
    <div class="flex justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold">Website Pages</h2>
            <p class="mt-1 text-sm text-slate-500">Edit public pages, services, projects, team members, client logos, images, and videos.</p>
        </div>
        <a href="{{ route('admin.cms.create') }}" class="px-4 py-2 bg-slate-900 text-white rounded-lg">New Page</a>
    </div>
    @include('admin.partials.search', ['action' => route('admin.cms.index'), 'placeholder' => 'Search website pages by title, key, slug, or type...'])

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50"><tr><th class="p-4 text-left">Title</th><th>Slug</th><th>Type</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @forelse($pages as $page)
                @php
                    $publicUrl = match ($page->key) {
                        'home' => route('website.home'),
                        'about', 'services', 'work', 'team', 'clients', 'contact' => url($page->slug),
                        default => route('website.page', $page->slug),
                    };
                @endphp
                <tr class="border-t">
                    <td class="p-4 font-semibold">{{ $page->title }}</td>
                    <td>{{ $page->slug }}</td>
                    <td>{{ data_get($page->sections, 'type', $page->key) }}</td>
                    <td>{{ $page->is_published ? 'Published' : 'Draft' }}</td>
                    <td class="space-x-3">
                        <a class="text-blue-600" href="{{ route('admin.cms.edit', $page) }}">Edit</a>
                        <a class="text-slate-600" href="{{ $publicUrl }}" target="_blank">View</a>
                    </td>
                </tr>
            @empty
                <tr><td class="p-4" colspan="4">No CMS pages yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $pages->links() }}</div>
</x-app-layout>
