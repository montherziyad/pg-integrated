<?php

namespace App\Modules\Settings\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255'],
            'company_email' => ['nullable', 'email', 'max:255'],
            'company_phone' => ['nullable', 'string', 'max:255'],
            'timezone' => ['required', 'timezone'],
            'date_format' => ['required', Rule::in(['Y-m-d', 'd-m-Y', 'd/m/Y', 'm/d/Y'])],
            'default_capacity_hours' => ['required', 'integer', 'min:1', 'max:24'],
            'job_number_prefix' => ['required', 'string', 'max:20', 'regex:/^[A-Za-z0-9_-]+$/'],
            'email_notifications' => ['nullable', 'boolean'],
            'outlook_enabled' => ['nullable', 'boolean'],
            'outlook_tenant_id' => ['nullable', 'required_if:outlook_enabled,1', 'uuid'],
            'outlook_client_id' => ['nullable', 'required_if:outlook_enabled,1', 'uuid'],
            'outlook_mailbox_address' => ['nullable', 'required_if:outlook_enabled,1', 'email', 'max:255'],
            'outlook_company_domain' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9.-]+$/'],
            'outlook_job_number_pattern' => ['required', 'string', 'max:255'],
            'outlook_allowed_extensions' => ['required', 'string', 'max:1000'],
            'outlook_max_attachment_mb' => ['required', 'integer', 'min:1', 'max:150'],
            'traffic_member_ids' => ['nullable', 'required_if:outlook_enabled,1', 'array', 'min:1'],
            'traffic_member_ids.*' => ['integer', 'exists:users,id'],
        ];
    }
}
