<x-app-layout>
    <x-slot name="header">Create Role</x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="pg-title">Create Role</h2>
                <p class="pg-subtitle">Add a new user role.</p>
            </div>

            <a href="{{ route('admin.roles.index') }}" class="pg-btn-secondary">
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

                <form method="POST" action="{{ route('admin.roles.store') }}" class="space-y-8">
                    @csrf

                    @include('admin.roles.form')

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.roles.index') }}" class="pg-btn-secondary">
                            Cancel
                        </a>

                        <button type="submit" class="pg-btn-primary">
                            Save Role
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
