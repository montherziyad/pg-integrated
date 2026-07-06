<?php

namespace App\Modules\Categories\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:job_categories,code'],
            'parent_id' => ['nullable', 'integer', 'exists:job_categories,id'],
            'description' => ['nullable', 'string'],
            'default_team' => ['nullable', 'string', 'max:255'],
            'estimated_hours' => ['nullable', 'numeric', 'min:0', 'max:9999'],
            'requires_approval' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
