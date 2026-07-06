<?php

namespace App\Modules\Clients\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_code' => ['required', 'string', 'max:255', 'unique:clients,client_code'],
            'name' => ['required', 'string', 'max:255'],
            'branch_id' => ['nullable', 'exists:branches,id'],
            'account_manager_id' => ['nullable', 'exists:users,id'],
            'client_service_user_ids' => ['nullable', 'array', 'max:3'],
            'client_service_user_ids.*' => ['exists:users,id'],
            'industry' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'required_if:portal_enabled,1', 'email', 'max:255', 'unique:clients,email'],
            'password' => ['nullable', 'required_if:portal_enabled,1', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'portal_enabled' => ['nullable', 'boolean'],
        ];
    }
}
