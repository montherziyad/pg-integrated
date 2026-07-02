<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class OutlookWebhookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'value' => ['nullable', 'array'],
            'value.*.subscriptionId' => ['required_with:value', 'string'],
            'value.*.clientState' => ['required_with:value', 'string'],
            'value.*.resource' => ['required_with:value', 'string'],
            'value.*.resourceData.id' => ['nullable', 'string'],
        ];
    }
}
