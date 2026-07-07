<?php

namespace App\Modules\Jobs\Requests;

use App\Models\EmployeeLeave;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class AssignJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'team_id' => ['required', 'exists:teams,id'],
            'supervisor_id' => ['nullable', 'exists:users,id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'estimated_hours' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $userId = $this->integer('user_id');

            if (! $userId) {
                return;
            }

            $leave = EmployeeLeave::query()
                ->where('user_id', $userId)
                ->where('status', 'approved')
                ->whereDate('starts_at', '<=', now()->toDateString())
                ->whereDate('ends_at', '>=', now()->toDateString())
                ->first();

            if ($leave) {
                $validator->errors()->add('user_id', 'This employee is on approved leave until '.$leave->ends_at->format('Y-m-d').' and returns on '.$leave->returns_at->format('Y-m-d').'.');
            }
        });
    }
}