<x-app-layout>
    <x-slot name="header">AI Employee</x-slot>

    @if (session('status'))
        <div class="mb-5 rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>
    @endif

    <div class="mb-6 rounded-2xl border border-amber-200 bg-amber-50 p-5 text-amber-900">
        <h2 class="text-lg font-bold">Approval-first AI mode</h2>
        <p class="mt-1 text-sm">The AI Employee only creates suggestions. Nothing is posted, created, or changed until an authorized employee clicks Approve.</p>
    </div>

    <section class="mb-6">
        <div class="mb-4 flex items-end justify-between">
            <div>
                <h2 class="text-2xl font-bold">AI Employees</h2>
                <p class="mt-1 text-sm text-slate-500">Digital employees, responsibilities, operating status, and mandatory guardrails.</p>
            </div>
        </div>
        <div class="grid gap-6 xl:grid-cols-2">
            @foreach($aiEmployees as $employee)
                <article class="rounded-2xl bg-white p-6 shadow">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="text-xs font-bold uppercase tracking-[.18em] text-slate-400">{{ $employee->department }}</div>
                            <h3 class="mt-2 text-2xl font-bold">{{ $employee->name }}</h3>
                            <p class="mt-1 font-semibold text-slate-600">{{ $employee->job_title }}</p>
                        </div>
                        <span class="rounded-full px-3 py-1 text-xs font-bold {{ $employee->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700' }}">
                            {{ ucfirst($employee->status) }}
                        </span>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-slate-600">{{ $employee->description }}</p>
                    <div class="mt-5 grid gap-5 md:grid-cols-2">
                        <div>
                            <h4 class="font-bold">Responsibilities</h4>
                            <ul class="mt-2 space-y-2 text-sm text-slate-600">
                                @foreach($employee->capabilities ?? [] as $capability)
                                    <li>• {{ $capability }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-bold">Guardrails</h4>
                            <ul class="mt-2 space-y-2 text-sm text-slate-600">
                                @foreach($employee->guardrails ?? [] as $guardrail)
                                    <li>• {{ $guardrail }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
                        <span class="text-sm font-semibold text-amber-700">{{ $employee->approval_required ? 'Approval required for every action' : 'Automatic actions enabled' }}</span>
                        @if(auth()->user()?->role?->code === 'SUPER_ADMIN')
                            <form method="POST" action="{{ route('ai-employee.employees.toggle', $employee) }}">
                                @csrf
                                <button class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-bold">
                                    {{ $employee->status === 'active' ? 'Pause employee' : 'Activate employee' }}
                                </button>
                            </form>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <section class="rounded-2xl bg-white p-6 shadow">
        <h2 class="text-2xl font-bold">Pending approvals</h2>
        <div class="mt-5 space-y-4">
            @forelse ($pendingSuggestions as $suggestion)
                <article class="rounded-2xl border border-slate-200 p-5">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div>
                            <div class="text-xs font-bold uppercase tracking-wide text-slate-400">{{ $suggestion->type }}</div>
                            <h3 class="mt-1 text-xl font-bold">{{ $suggestion->title }}</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">{{ $suggestion->summary }}</p>
                            <pre class="mt-3 max-h-56 overflow-auto rounded-xl bg-slate-50 p-4 text-xs">{{ json_encode($suggestion->payload, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) }}</pre>
                        </div>
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('ai-employee.suggestions.approve', $suggestion) }}">
                                @csrf
                                <button class="rounded-xl bg-slate-950 px-4 py-2 text-sm font-bold text-white">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('ai-employee.suggestions.reject', $suggestion) }}">
                                @csrf
                                <button class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-bold">Reject</button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <p class="text-slate-500">No suggestions are waiting for approval.</p>
            @endforelse
        </div>
    </section>

    <div class="mt-6 grid gap-6 xl:grid-cols-2">
        <section class="rounded-2xl bg-white p-6 shadow">
            <h2 class="text-xl font-bold">Client requests monitor</h2>
            <div class="mt-4 space-y-3">
                @forelse ($clientRequests as $clientRequest)
                    <div class="rounded-xl border border-slate-200 p-4">
                        <div class="font-bold">{{ $clientRequest->title }}</div>
                        <div class="text-sm text-slate-500">{{ $clientRequest->client?->name }} · {{ $clientRequest->type }} · {{ $clientRequest->status }}</div>
                        <form method="POST" action="{{ route('ai-employee.client-requests.suggest', $clientRequest) }}" class="mt-3">
                            @csrf
                            <button class="rounded-lg bg-slate-100 px-3 py-2 text-sm font-bold">Suggest next step</button>
                        </form>
                    </div>
                @empty
                    <p class="text-slate-500">No client requests yet.</p>
                @endforelse
            </div>
        </section>

        <section class="rounded-2xl bg-white p-6 shadow">
            <h2 class="text-xl font-bold">Support tickets monitor</h2>
            <div class="mt-4 space-y-3">
                @forelse ($supportTickets as $ticket)
                    <div class="rounded-xl border border-slate-200 p-4">
                        <div class="font-bold">{{ $ticket->subject }}</div>
                        <div class="text-sm text-slate-500">{{ $ticket->ticket_number }} · {{ $ticket->status }} · {{ $ticket->priority }}</div>
                        <form method="POST" action="{{ route('ai-employee.support.suggest-reply', $ticket) }}" class="mt-3">
                            @csrf
                            <button class="rounded-lg bg-slate-100 px-3 py-2 text-sm font-bold">Draft reply for approval</button>
                        </form>
                    </div>
                @empty
                    <p class="text-slate-500">No open support tickets.</p>
                @endforelse
            </div>
        </section>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-[.8fr_1.2fr]">
        <section class="rounded-2xl bg-white p-6 shadow">
            <h2 class="text-xl font-bold">CRM research proposal</h2>
            <p class="mt-1 text-sm text-slate-500">Paste LinkedIn/company information manually or from an approved export. AI will create a proposal only.</p>
            <form method="POST" action="{{ route('ai-employee.crm.suggest-lead') }}" class="mt-5 space-y-4">
                @csrf
                <input name="company_name" required placeholder="Company name" class="w-full rounded-xl border-slate-300">
                <input name="linkedin_url" placeholder="LinkedIn URL" class="w-full rounded-xl border-slate-300">
                <input name="website" placeholder="Website" class="w-full rounded-xl border-slate-300">
                <div class="grid gap-4 md:grid-cols-2">
                    <input name="industry" placeholder="Industry" class="w-full rounded-xl border-slate-300">
                    <input name="country" placeholder="Country" class="w-full rounded-xl border-slate-300">
                </div>
                <textarea name="notes" rows="4" placeholder="Research notes" class="w-full rounded-xl border-slate-300"></textarea>
                <button class="rounded-xl bg-slate-950 px-5 py-3 font-bold text-white">Create CRM suggestion</button>
            </form>
        </section>

        <section class="rounded-2xl bg-white p-6 shadow">
            <h2 class="text-xl font-bold">Operations monitor</h2>
            <div class="mt-4 overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr><th class="p-3">Job</th><th>Client</th><th>Stage</th><th>Progress</th><th>Due</th></tr>
                    </thead>
                    <tbody>
                    @forelse ($jobs as $job)
                        <tr class="border-t">
                            <td class="p-3"><div class="font-bold">{{ $job->title }}</div><div class="text-xs text-slate-500">{{ $job->job_number }}</div></td>
                            <td>{{ $job->client?->name }}</td>
                            <td>{{ $job->currentWorkflowStage?->name ?? 'Preparing' }}</td>
                            <td>{{ $job->completion_percentage }}%</td>
                            <td>{{ $job->final_due_at?->format('Y-m-d') ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="p-6 text-slate-500">No active jobs.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>
