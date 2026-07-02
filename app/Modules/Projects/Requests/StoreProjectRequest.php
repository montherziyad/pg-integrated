<?php

namespace App\Modules\Projects\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_code' => ['required', 'string', 'max:255', 'unique:projects,project_code'],
            'client_id' => ['required', 'exists:clients,id'], 'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'], 'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'project_manager_id' => ['nullable', 'exists:users,id'], 'is_active' => ['nullable', 'boolean'],
        ];
    }
}
