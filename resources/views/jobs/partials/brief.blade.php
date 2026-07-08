<div class="pg-card">
    <div class="pg-card-body">
        <h3 class="text-lg font-bold mb-4">Brief</h3>

        @php
            $raw = $job->brief ?? '';
            $plain = trim(strip_tags($raw));
        @endphp

        @if(empty($plain))
            <p class="text-slate-700">No brief provided.</p>
        @else
            <div x-data="{ showRaw: false }">
                <div class="mb-3">
                    <button type="button" @click="showRaw = !showRaw" class="text-sm text-sky-600 underline">Toggle original HTML</button>
                </div>

                <div x-show="!showRaw">
                    <pre class="text-slate-700 whitespace-pre-wrap rounded p-3 bg-slate-50 border border-slate-100">{{ $plain }}</pre>
                </div>

                <div x-show="showRaw" x-cloak>
                    <label class="block mb-2 text-xs text-slate-500">Raw HTML (escaped):</label>
                    <pre class="text-xs text-slate-700 whitespace-pre-wrap rounded p-3 bg-black text-white overflow-auto">{{ e($raw) }}</pre>
                </div>
            </div>
        @endif

    </div>
</div>