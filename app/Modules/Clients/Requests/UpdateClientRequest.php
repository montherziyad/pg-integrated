<?php

namespace App\Modules\Clients\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $clientId = $this->route('client')?->id ?? $this->route('client');

        return [
            'client_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('clients', 'client_code')->ignore($clientId),
            ],
            'name' => ['required', 'string', 'max:255'],
            'branch_id' => ['nullable', 'exists:branches,id'],
            'account_manager_id' => ['nullable', 'exists:users,id'],
            'industry' => ['nullable', 'string', 'max:255'],
            'email' => [
                'nullable',
                'required_if:portal_enabled,1',
                'email',
                'max:255',
                Rule::unique('clients', 'email')->ignore($clientId),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'portal_enabled' => ['nullable', 'boolean'],
        ];
    }
}
