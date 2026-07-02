<?php

namespace App\Modules\EmailIntakes\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectEmailIntakeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return ['rejection_reason' => ['required', 'string', 'min:5', 'max:2000']];
    }
}
