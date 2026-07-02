<x-app-layout>
    <x-slot name="header">Edit Branch</x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="pg-title">Edit Branch</h2>
                <p class="pg-subtitle">{{ $branch->name }}</p>
            </div>

            <a href="{{ route('admin.branches.show', $branch->id) }}" class="pg-btn-secondary">
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

                <form method="POST" action="{{ route('admin.branches.update', $branch->id) }}" class="space-y-8">
                    @csrf
                    @method('PUT')

                    @include('admin.branches.form')

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.branches.show', $branch->id) }}" class="pg-btn-secondary">
                            Cancel
                        </a>

                        <button type="submit" class="pg-btn-primary">
                            Update Branch
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
