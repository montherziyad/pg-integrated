<x-app-layout>
    <x-slot name="header">CRM</x-slot>
    <div class="flex justify-between mb-6"><h2 class="text-2xl font-bold">Companies Pipeline</h2><a href="{{ route('crm.create') }}" class="px-4 py-2 bg-slate-900 text-white rounded-lg">Add Company</a></div>
    <div class="grid md:grid-cols-5 gap-3 mb-6">
        @foreach(['new','contacted','interested','proposal','negotiation','won'] as $status)
            <a href="?status={{ $status }}" class="bg-white rounded-xl p-4 shadow"><div class="text-xs uppercase text-slate-500">{{ $status }}</div><div class="text-xl font-bold">{{ $companies->where('status',$status)->count() }}</div></a>
        @endforeach
    </div>
    <div class="bg-white rounded-xl shadow overflow-hidden"><table class="w-full text-sm"><thead class="bg-slate-50"><tr><th class="p-4 text-left">Company</th><th>Status</th><th>Score</th><th>Owner</th><th>Follow-up</th></tr></thead><tbody>
        @forelse($companies as $company)
        <tr class="border-t"><td class="p-4 font-semibold"><a class="text-blue-700" href="{{ route('crm.show',$company) }}">{{ $company->name }}</a><div class="text-xs text-slate-500">{{ $company->industry }}</div></td><td>{{ $company->status }}</td><td>{{ $company->lead_score }}%</td><td>{{ $company->owner?->name ?? '-' }}</td><td>{{ $company->next_follow_up_at?->format('Y-m-d') ?? '-' }}</td></tr>
        @empty <tr><td class="p-4" colspan="5">No companies yet.</td></tr>@endforelse
    </tbody></table></div><div class="mt-4">{{ $companies->links() }}</div>
</x-app-layout>
