<form method="GET" action="{{ $action }}" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
    <div class="flex flex-col gap-3 md:flex-row md:items-center">
        <div class="flex-1">
            <label class="sr-only" for="admin-search-{{ md5($action) }}">Search</label>
            <input id="admin-search-{{ md5($action) }}"
                   type="search"
                   name="q"
                   value="{{ request('q') }}"
                   placeholder="{{ $placeholder ?? 'Search records...' }}"
                   class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-slate-900 focus:ring-slate-900">
        </div>
        <div class="flex gap-2">
            <button class="rounded-xl bg-slate-950 px-5 py-3 text-sm font-bold text-white hover:bg-slate-800">Search</button>
            @if(request('q'))
                <a href="{{ $action }}" class="rounded-xl border border-slate-300 px-5 py-3 text-sm font-bold text-slate-600 hover:bg-slate-50">Clear</a>
            @endif
        </div>
    </div>
</form>
