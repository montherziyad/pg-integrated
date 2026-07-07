<x-app-layout>
    <x-slot name="header">Role Details</x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="pg-title">{{ $role->name }}</h2>
                <p class="pg-subtitle">{{ $role->code }}</p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('admin.roles.edit', $role->id) }}" class="pg-btn-primary">
                    Edit
                </a>

                <a href="{{ route('admin.roles.index') }}" class="pg-btn-secondary">
                    Back
                </a>
            </div>
        </div>

        <div class="pg-card">
            <div class="pg-card-body space-y-4">
                <div>
                    <div class="pg-stat-label">Description</div>
                    <div class="font-semibold">{{ $role->description ?? '-' }}</div>
                </div>

                <div>
                    <div class="pg-stat-label">Status</div>
                    <div class="font-semibold">
                        {{ $role->is_active ? 'Active' : 'Inactive' }}
                    </div>
                </div>
            </div>
        </div>



        <div class="pg-card">
            <div class="pg-card-body">
                <div class="mb-5 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold">Screen Access</h3>
                        <p class="text-sm text-slate-500">Screens this role can see and open in the dashboard.</p>
                    </div>
                </div>

                @php $enabledPermissions = $role->enabledScreenPermissions(); @endphp

                <div class="space-y-5">
                    @foreach($screenPermissionGroups ?? [] as $group => $screens)
                        @php $visibleScreens = collect($screens)->filter(fn ($screen, $key) => data_get($enabledPermissions, $key, false)); @endphp

                        @if($visibleScreens->isNotEmpty())
                            <div>
                                <div class="mb-2 text-xs font-black uppercase tracking-[.22em] text-slate-500">{{ $group }}</div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($visibleScreens as $key => $screen)
                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-700">{{ $screen['label'] }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <div class="pg-card">
            <div class="pg-card-body">
                <h3 class="text-lg font-bold mb-4">Users With This Role</h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b text-left text-slate-500">
                                <th class="py-3">Name</th>
                                <th>Email</th>
                                <th>Team</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($role->users as $user)
                                <tr class="border-b last:border-b-0">
                                    <td class="py-3">{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->team?->name ?? '-' }}</td>
                                    <td>{{ $user->is_active ? 'Active' : 'Inactive' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-slate-500">
                                        No users assigned to this role.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
