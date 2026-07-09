<x-app-layout>
    <x-slot name="header">Delivery / Handover</x-slot>

    <div class="space-y-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
            <div>
                <h2 class="pg-title">{{ $job->job_number }} — {{ $job->title }}</h2>
                <p class="pg-subtitle mt-1">This page is only for delivery links, production handover, and client publishing. Brief requirements stay protected in the Job page.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('jobs.show', $job) }}" class="pg-btn-secondary">View Job Brief</a>
                <a href="{{ route('deliveries.index') }}" class="pg-btn-secondary">Search Jobs</a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid gap-6 xl:grid-cols-[.9fr_1.1fr]">
            <aside class="pg-card">
                <div class="pg-card-body space-y-4">
                    <h3 class="text-xl font-bold">Job summary</h3>
                    @foreach([
                        'Client' => $job->client?->name ?? '-',
                        'Project' => $job->project?->name ?? '-',
                        'Category' => $job->category?->name ?? '-',
                        'Priority' => $job->priority,
                        'Workflow Stage' => $job->currentWorkflowStage?->name ?? '-',
                        'Team Delivery Commitment' => $job->production_due_at?->format('Y-m-d H:i') ?? 'Waiting for team response',
                        'Production Handover' => $job->employee_handover_status === 'submitted_to_traffic' ? 'Submitted to Traffic' : 'Pending',
                        'Estimated Hours' => number_format((float) $job->estimated_hours, 2),
                        'Delivery Status' => str($job->delivery_review_status ?? 'draft')->replace('_', ' ')->title(),
                    ] as $label => $value)
                        <div>
                            <div class="pg-stat-label">{{ $label }}</div>
                            <div class="font-semibold">{{ $value }}</div>
                        </div>
                    @endforeach
                </div>
            </aside>

            <form method="POST" action="{{ route('deliveries.update', $job) }}" class="pg-card">
                @csrf
                @method('PUT')

                <div class="pg-card-body space-y-6">
                    <div>
                        <h3 class="text-xl font-bold">What was delivered</h3>
                        <p class="mt-1 text-sm text-slate-500">هذه الصفحة منفصلة عن البريف. استخدمها فقط للروابط النهائية، ملفات الإنتاج، وما تم تسليمه للعميل.</p>
                    </div>

                    @if($job->employee_handover_link || $job->employee_handover_notes || $job->employee_handover_submitted_at)
                        <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4">
                            <div class="text-xs font-black uppercase tracking-wide text-blue-500">Production handover from designer</div>
                            <div class="mt-2 text-sm text-blue-950">
                                This is the original handover sent to Traffic. Use the same link for review, then approve it for Client Service.
                            </div>
                            <div class="mt-3 space-y-2 text-sm">
                                @if($job->employee_handover_link)
                                    <div>
                                        <span class="font-bold text-slate-700">Submitted link:</span>
                                        <a href="{{ $job->employee_handover_link }}" target="_blank" rel="noopener" class="font-bold text-blue-700 underline">
                                            {{ $job->employee_handover_link }}
                                        </a>
                                    </div>
                                @endif

                                @if($job->employee_handover_submitted_at)
                                    <div>
                                        <span class="font-bold text-slate-700">Submitted at:</span>
                                        {{ $job->employee_handover_submitted_at?->format('Y-m-d H:i') }}
                                    </div>
                                @endif

                                @if($job->employee_handover_notes)
                                    <div>
                                        <span class="font-bold text-slate-700">Notes:</span>
                                        {{ $job->employee_handover_notes }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <label class="block mb-2 font-semibold">Client Service review status</label>
                        <select name="delivery_review_status" class="w-full rounded-xl border-slate-300">
                            @foreach([
                                'draft' => 'Draft — internal only, not visible to client',
                                'checked' => 'Checked by Client Service — ready but not published',
                                'published' => 'Published to Client Portal — client can see delivery link',
                            ] as $value => $label)
                                <option value="{{ $value }}" @selected(old('delivery_review_status', $job->delivery_review_status ?? 'draft') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <p class="mt-2 text-xs text-slate-500">
                            Only publish after the Client Service employee checks the final output. The final link stays hidden from the client until status is Published.
                        </p>
                        @if($job->deliveryReviewer)
                            <p class="mt-2 text-xs font-semibold text-slate-600">
                                Last checked by {{ $job->deliveryReviewer->name }} at {{ $job->delivery_reviewed_at?->format('Y-m-d H:i') ?? '-' }}.
                            </p>
                        @endif
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="block mb-2 font-semibold">Stage shown to client</label>
                            <select name="current_workflow_stage_id" class="w-full rounded-xl border-slate-300">
                                <option value="">Preparing / Not selected</option>
                                @foreach($workflowStages as $stage)
                                    <option value="{{ $stage->id }}" @selected((string) old('current_workflow_stage_id', $job->current_workflow_stage_id) === (string) $stage->id)>{{ $stage->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block mb-2 font-semibold">Progress %</label>
                            <input name="completion_percentage" type="number" min="0" max="100" value="{{ old('completion_percentage', $job->completion_percentage) }}" class="w-full rounded-xl border-slate-300">
                        </div>

                        <div>
                            <label class="block mb-2 font-semibold">Brief / Dropbox link</label>
                            <input name="dropbox_folder_path" value="{{ old('dropbox_folder_path', $job->dropbox_folder_path) }}" placeholder="https://www.dropbox.com/..." class="w-full rounded-xl border-slate-300">
                            <p class="mt-1 text-xs text-slate-500">For client-accessible brief or production folder links only.</p>
                        </div>

                        <div>
                            <label class="block mb-2 font-semibold">Final / WeTransfer link</label>
                            <input name="final_delivery_path" value="{{ old('final_delivery_path', $job->final_delivery_path ?: $job->employee_handover_link) }}" placeholder="https://wetransfer.com/..." class="w-full rounded-xl border-slate-300">
                            <p class="mt-1 text-xs text-slate-500">Auto-filled from the designer handover link when no final link has been saved yet.</p>
                        </div>

                        <div>
                            <label class="block mb-2 font-semibold">Final delivered at</label>
                            <input name="final_delivered_at" type="datetime-local" value="{{ old('final_delivered_at', $job->final_delivered_at?->format('Y-m-d\\TH:i')) }}" class="w-full rounded-xl border-slate-300">
                        </div>
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold">What was delivered / Client delivery note</label>
                        <textarea name="client_notes" rows="5" class="w-full rounded-xl border-slate-300" placeholder="Example: Final campaign strategy deck, approved key visual, and editable production files delivered.">{{ old('client_notes', $job->client_notes) }}</textarea>
                    </div>

                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-900">
                        <span class="block font-bold text-emerald-950">Publishing rule</span>
                        <span class="mt-1 block">When Client Service changes the status to Published, the delivery becomes visible to the client portal and this job moves automatically to Completed Jobs.</span>
                    </div>

                    <div class="flex justify-end gap-3 border-t border-slate-200 pt-5">
                        <a href="{{ route('deliveries.index') }}" class="pg-btn-secondary">Cancel</a>
                        <button class="pg-btn-primary">Save Delivery / Approval</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
