<?php

namespace App\Modules\Projects\Requests;

use Illuminate\Validation\Rule;

class UpdateProjectRequest extends StoreProjectRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        $id = $this->route('project')?->id ?? $this->route('project');
        $rules['project_code'] = ['required', 'string', 'max:255', Rule::unique('projects', 'project_code')->ignore($id)];

        return $rules;
    }
}
