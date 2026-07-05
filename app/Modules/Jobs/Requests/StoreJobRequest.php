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
            'current_workflow_stage_id' => ['nullable', 'exists:workflow_stages,id'],
            'completion_percentage' => ['nullable', 'integer', 'min:0', 'max:100'],
            'client_notes' => ['nullable', 'string'],
            'dropbox_folder_path' => ['nullable', 'string', 'max:1000'],
            'final_delivery_path' => ['nullable', 'string', 'max:1000'],
            'brief' => ['nullable', 'string'],
            'attachments.*' => ['nullable', 'file', 'max:51200'],
        ];
    }
}
