<div class="pg-card">
    <div class="pg-card-body">
        <h3 class="text-lg font-bold mb-4">Assignment</h3>

        <form method="POST" action="{{ route('jobs.assign', $job->id) }}" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf

            <div>
                <label class="block mb-2 font-semibold">Team / Department</label>
                <select name="team_id" class="w-full rounded-xl border-slate-300" required>
                    <option value="">Select Team</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-2 font-semibold">Leads / Supervisors</label>

                <div x-data="{
                    query: '',
                    results: [],
                    selected: @json([]),
                    name: 'supervisor_ids[]',
                    async search() {
                        if (this.query.length < 2) { this.results = []; return; }
                        const res = await fetch(`{{ route('users.search') }}?q=` + encodeURIComponent(this.query));
                        this.results = await res.json();
                    },
                    select(user) {
                        if (!this.selected.find(u => u.id === user.id)) {
                            this.selected.push(user);
                        }
                        this.query = '';
                        this.results = [];
                    },
                    remove(user) { this.selected = this.selected.filter(u => u.id !== user.id); }
                }" class="relative">

                    <input x-model="query" @input.debounce.300ms="search()" type="search" placeholder="Search name or email..." class="w-full rounded-xl border-slate-300 px-3 py-2" />

                    <div x-show="results.length" class="absolute z-50 left-0 right-0 mt-1 bg-white border rounded shadow max-h-60 overflow-auto">
                        <template x-for="user in results" :key="user.id">
                            <div @click.prevent="select(user)" class="p-2 hover:bg-slate-50 cursor-pointer border-b last:border-b-0">
                                <div class="font-medium" x-text="user.name"></div>
                                <div class="text-xs text-slate-500" x-text="user.email"></div>
                            </div>
                        </template>
                    </div>

                    <div class="mt-2 flex flex-wrap gap-2">
                        <template x-for="user in selected" :key="user.id">
                            <span class="inline-flex items-center gap-2 bg-slate-100 px-3 py-1 rounded-full text-sm">
                                <span x-text="user.name"></span>
                                <button type="button" class="text-slate-500" @click.prevent="remove(user)">✕</button>
                                <input type="hidden" :name="name" :value="user.id" />
                            </span>
                        </template>
                    </div>

                </div>

                <p class="mt-2 text-xs text-slate-500">Search and add supervisors quickly.</p>
            </div>

            <div>
                <label class="block mb-2 font-semibold">Designers / Team members</label>

                <div x-data="{
                    query: '',
                    results: [],
                    selected: @json($job->assignedDesigners()->map(fn($u)=>['id'=>$u->id,'name'=>$u->name])->values()),
                    name: 'user_ids[]',
                    async search() {
                        if (this.query.length < 2) { this.results = []; return; }
                        const res = await fetch(`{{ route('users.search') }}?q=` + encodeURIComponent(this.query));
                        this.results = await res.json();
                    },
                    select(user) {
                        if (!this.selected.find(u => u.id === user.id)) {
                            this.selected.push(user);
                        }
                        this.query = '';
                        this.results = [];
                    },
                    remove(user) { this.selected = this.selected.filter(u => u.id !== user.id); }
                }" class="relative">

                    <input x-model="query" @input.debounce.300ms="search()" type="search" placeholder="Search name or email..." class="w-full rounded-xl border-slate-300 px-3 py-2" />

                    <div x-show="results.length" class="absolute z-50 left-0 right-0 mt-1 bg-white border rounded shadow max-h-60 overflow-auto">
                        <template x-for="user in results" :key="user.id">
                            <div @click.prevent="select(user)" class="p-2 hover:bg-slate-50 cursor-pointer border-b last:border-b-0">
                                <div class="font-medium" x-text="user.name"></div>
                                <div class="text-xs text-slate-500" x-text="user.email"></div>
                            </div>
                        </template>
                    </div>

                    <div class="mt-2 flex flex-wrap gap-2">
                        <template x-for="user in selected" :key="user.id">
                            <span class="inline-flex items-center gap-2 bg-slate-100 px-3 py-1 rounded-full text-sm">
                                <span x-text="user.name"></span>
                                <button type="button" class="text-slate-500" @click.prevent="remove(user)">✕</button>
                                <input type="hidden" :name="name" :value="user.id" />
                            </span>
                        </template>
                    </div>

                </div>

                <p class="mt-2 text-xs text-slate-500">Search and add team members quickly.</p>
            </div>

            <div>
                <label class="block mb-2 font-semibold">Estimated Hours</label>
                <input type="number" step="0.5" min="0" name="estimated_hours" class="w-full rounded-xl border-slate-300">
            </div>

            <div class="md:col-span-2">
                <label class="block mb-2 font-semibold">Notes</label>
                <textarea name="notes" rows="3" class="w-full rounded-xl border-slate-300"></textarea>
            </div>

            <div class="md:col-span-2">
                <button type="submit" class="pg-btn-primary">
                    Assign Job
                </button>
            </div>
        </form>

        @include('jobs.partials.assignment-history')
    </div>
</div>
