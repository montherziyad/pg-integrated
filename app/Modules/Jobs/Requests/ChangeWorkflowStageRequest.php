<?php

namespace App\Modules\Jobs\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeWorkflowStageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'workflow_stage_id' => ['required', 'exists:workflow_stages,id'],
            'notes' => ['nullable', 'string'],
        ];
    }
}