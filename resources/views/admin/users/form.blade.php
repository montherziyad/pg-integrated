<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <div>
        <label class="block mb-2 font-semibold">Name</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}"
               class="w-full rounded-xl border-slate-300" required>
    </div>

    <div>
        <label class="block mb-2 font-semibold">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}"
               class="w-full rounded-xl border-slate-300" required>
    </div>

    <div>
        <label class="block mb-2 font-semibold">Password</label>
        <input type="password" name="password"
               class="w-full rounded-xl border-slate-300"
               @if(!$user->id) required @endif>
    </div>

    <div>
        <label class="block mb-2 font-semibold">Employee No.</label>
        <input type="text" name="employee_no" value="{{ old('employee_no', $user->employee_no) }}"
               class="w-full rounded-xl border-slate-300">
    </div>

    <div>
        <label class="block mb-2 font-semibold">Branch</label>
        <select name="branch_id" class="w-full rounded-xl border-slate-300">
            <option value="">Select Branch</option>
            @foreach($branches as $branch)
                <option value="{{ $branch->id }}" @selected(old('branch_id', $user->branch_id) == $branch->id)>
                    {{ $branch->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block mb-2 font-semibold">Team</label>
        <select name="team_id" class="w-full rounded-xl border-slate-300">
            <option value="">Select Team</option>
            @foreach($teams as $team)
                <option value="{{ $team->id }}" @selected(old('team_id', $user->team_id) == $team->id)>
                    {{ $team->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block mb-2 font-semibold">Role</label>
        <select name="role_id" class="w-full rounded-xl border-slate-300">
            <option value="">Select Role</option>
            @foreach($roles as $role)
                <option value="{{ $role->id }}" @selected(old('role_id', $user->role_id) == $role->id)>
                    {{ $role->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block mb-2 font-semibold">Job Title</label>
        <input type="text" name="job_title" value="{{ old('job_title', $user->job_title) }}"
               class="w-full rounded-xl border-slate-300">
    </div>

    <div>
        <label class="block mb-2 font-semibold">Mobile</label>
        <input type="text" name="mobile" value="{{ old('mobile', $user->mobile) }}"
               class="w-full rounded-xl border-slate-300">
    </div>

    <div>
        <label class="block mb-2 font-semibold">Capacity Hours</label>
        <input type="number" name="capacity_hours" min="1"
               value="{{ old('capacity_hours', $user->capacity_hours ?? 8) }}"
               class="w-full rounded-xl border-slate-300">
    </div>

    <div class="md:col-span-2">
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1"
                   @checked(old('is_active', $user->is_active ?? true))>
            <span class="font-semibold">Active</span>
        </label>
    </div>

</div>