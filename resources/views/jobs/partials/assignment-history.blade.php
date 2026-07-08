<div class="mt-8">
    <h4 class="text-md font-bold mb-4">Assignment History</h4>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b text-left text-slate-500">
                    <th class="py-3">Team</th>
                    <th>Lead / Supervisor</th>
                    <th>Designer / Team Member</th>
                    <th>Hours</th>
                    <th>Status</th>
                    <th>Assigned At</th>
                </tr>
            </thead>

            <tbody>
                @forelse($job->assignments as $assignment)
                    <tr class="border-b last:border-b-0">
                        <td class="py-3">{{ $assignment->team?->name ?? '-' }}</td>
                        <td>
                            @if($assignment->supervisor)
                                <div class="font-semibold">{{ $assignment->supervisor->name }}</div>
                                <div class="text-xs text-slate-500">{{ $assignment->supervisor->email }}</div>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($assignment->assignee)
                                <div class="font-semibold">{{ $assignment->assignee->name }}</div>
                                <div class="text-xs text-slate-500">{{ $assignment->assignee->email }}</div>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $assignment->estimated_hours }}</td>
                        <td>{{ $assignment->status }}</td>
                        <td>
                            {{ $assignment->assigned_at ? \Carbon\Carbon::parse($assignment->assigned_at)->format('Y-m-d H:i') : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-6 text-center text-slate-500">
                            No assignments yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
