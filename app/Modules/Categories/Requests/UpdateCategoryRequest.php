<?php

namespace App\Modules\Categories\Requests;

use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends StoreCategoryRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        $id = $this->route('category')?->id ?? $this->route('category');
        $rules['code'] = ['required', 'string', 'max:255', Rule::unique('job_categories', 'code')->ignore($id)];

        return $rules;
    }
}
