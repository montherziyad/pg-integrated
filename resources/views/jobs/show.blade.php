<x-app-layout>
    <x-slot name="header">
        Job Details
    </x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="pg-title">{{ $job->job_number }}</h2>
                <p class="pg-subtitle">{{ $job->title }}</p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('jobs.edit', $job) }}" class="pg-btn-primary">Edit Job</a>
                <a href="{{ route('jobs.index') }}" class="pg-btn-secondary">Back to Jobs</a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 space-y-6">
                @include('jobs.partials.brief')
                @include('jobs.partials.attachments')
                @include('jobs.partials.assignment')
                @include('jobs.partials.timeline')
                @include('jobs.partials.activity-log')
            </div>

            @include('jobs.partials.sidebar')
        </div>
    </div>
</x-app-layout>
