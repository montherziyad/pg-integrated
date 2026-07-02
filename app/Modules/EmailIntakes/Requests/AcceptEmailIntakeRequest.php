<?php

namespace App\Modules\EmailIntakes\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AcceptEmailIntakeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required', 'exists:clients,id'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'job_category_id' => ['nullable', 'exists:job_categories,id'],
            'title' => ['nullable', 'string', 'max:255'],
            'priority' => ['required', 'in:LOW,MEDIUM,HIGH,URGENT,CRITICAL'],
            'first_draft_due_at' => ['nullable', 'date'],
            'final_due_at' => ['nullable', 'date', 'after_or_equal:first_draft_due_at'],
            'estimated_hours' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
