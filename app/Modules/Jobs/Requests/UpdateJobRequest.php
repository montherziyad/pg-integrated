<?php

namespace App\Modules\Jobs\Requests;

class UpdateJobRequest extends StoreJobRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        unset($rules['attachments.*']);

        return $rules;
    }
}
