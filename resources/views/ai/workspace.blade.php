<x-app-layout>
    <x-slot name="header">AI Workspace</x-slot>

    @if (session('status'))
        <div class="mb-5 rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-5 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-800">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-2">
        <form method="POST" action="{{ route('ai.draft') }}" class="space-y-4 rounded-xl bg-white p-6 shadow">
            @csrf
            <div>
                <h2 class="text-xl font-bold">Generate Draft</h2>
                <p class="mt-1 text-sm text-slate-500">Choose an assistant and an available AI provider.</p>
            </div>

            <label class="block">
                <span class="mb-1 block text-sm font-medium text-slate-700">Assistant</span>
                <select name="agent" class="w-full rounded border-slate-300">
                    @foreach($assistants as $group => $groupAssistants)
                        <optgroup label="{{ $group }}">
                            @foreach($groupAssistants as $assistant)
                                <option value="{{ $assistant['value'] }}" @selected(old('agent', 'brief_analyzer') === $assistant['value'])>{{ $assistant['label'] }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </label>

            <label class="block">
                <span class="mb-1 block text-sm font-medium text-slate-700">AI Provider</span>
                <select name="provider" class="w-full rounded border-slate-300">
                    @foreach ($providers as $provider)
                        <option
                            value="{{ $provider['name'] }}"
                            @selected(old('provider', $defaultProvider) === $provider['name'])
                        >
                            {{ $provider['label'] }} · {{ $provider['model'] }}
                            {{ $provider['configured'] ? '' : '(API key required)' }}
                        </option>
                    @endforeach
                </select>
            </label>

            <label class="block">
                <span class="mb-1 block text-sm font-medium text-slate-700">Client request or task</span>
                <textarea
                    name="prompt"
                    rows="10"
                    class="w-full rounded border-slate-300"
                    placeholder="Paste the client request or describe the task..."
                    required
                >{{ old('prompt') }}</textarea>
            </label>

            <button class="rounded-lg bg-slate-900 px-5 py-2 text-white">Generate</button>
        </form>

        <div class="rounded-xl bg-white p-6 shadow">
            <h2 class="mb-4 text-xl font-bold">Recent AI Outputs</h2>

            <div class="space-y-4">
                @forelse ($interactions as $item)
                    <article class="border-b pb-4">
                        <div class="mb-2 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                            <span>{{ $item->agent }}</span>
                            <span>·</span>
                            <span>{{ $item->provider }}</span>
                            @if (data_get($item->meta, 'model'))
                                <span>· {{ data_get($item->meta, 'model') }}</span>
                            @endif
                            <span>· {{ $item->created_at->format('Y-m-d H:i') }}</span>
                        </div>

                        @if ($item->response)
                            <pre class="whitespace-pre-wrap font-sans text-sm text-slate-800">{{ $item->response }}</pre>
                        @else
                            <p class="text-sm text-red-600">
                                {{ data_get($item->meta, 'status') === 'failed' ? 'Generation failed.' : 'Generating…' }}
                            </p>
                        @endif
                    </article>
                @empty
                    <p class="text-sm text-slate-500">No AI drafts yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
