<x-app-layout>
<x-slot name="header">Customer Support</x-slot>
<div class="flex justify-between mb-6"><h2 class="text-2xl font-bold">Tickets</h2><a href="{{ route('support.create') }}" class="px-4 py-2 bg-slate-900 text-white rounded-lg">New Ticket</a></div>
<div class="bg-white rounded-xl shadow overflow-hidden"><table class="w-full text-sm"><thead class="bg-slate-50"><tr><th class="p-4 text-left">Ticket</th><th>Status</th><th>Priority</th><th>Assignee</th><th>Created</th></tr></thead><tbody>
@forelse($tickets as $ticket)<tr class="border-t"><td class="p-4"><a class="font-semibold text-blue-700" href="{{ route('support.show',$ticket) }}">{{ $ticket->ticket_number }}</a><div>{{ $ticket->subject }}</div></td><td>{{ $ticket->status }}</td><td>{{ $ticket->priority }}</td><td>{{ $ticket->assignee?->name ?? '-' }}</td><td>{{ $ticket->created_at->format('Y-m-d') }}</td></tr>@empty<tr><td class="p-4" colspan="5">No tickets yet.</td></tr>@endforelse
</tbody></table></div><div class="mt-4">{{ $tickets->links() }}</div>
</x-app-layout>
