<x-app-layout>
    <x-slot name="header">Edit Team</x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="pg-title">Edit Team</h2>
                <p class="pg-subtitle">{{ $team->name }}</p>
            </div>

            <a href="{{ route('admin.teams.show', $team->id) }}" class="pg-btn-secondary">
                ← Back
            </a>
        </div>

        <div class="pg-card">
            <div class="pg-card-body">

                @if($errors->any())
                    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.teams.update', $team->id) }}" class="space-y-8">
                    @csrf
                    @method('PUT')

                    @include('admin.teams.form')

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.teams.show', $team->id) }}" class="pg-btn-secondary">
                            Cancel
                        </a>

                        <button type="submit" class="pg-btn-primary">
                            Update Team
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>