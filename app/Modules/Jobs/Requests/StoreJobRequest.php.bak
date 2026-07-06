<?php

namespace App\Modules\Jobs\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'client_id' => ['required', 'exists:clients,id'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'job_category_id' => ['nullable', 'exists:job_categories,id'],
            'priority' => ['required', 'in:LOW,MEDIUM,HIGH,URGENT,CRITICAL'],
            'first_draft_due_at' => ['nullable', 'date'],
            'final_due_at' => ['nullable', 'date'],
            'estimated_hours' => ['nullable', 'numeric', 'min:0'],
            'brief' => ['nullable', 'string'],
            'attachments.*' => ['nullable', 'file', 'max:51200'],
        ];
    }
}