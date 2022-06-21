<?php

namespace Jpeters8889\Architect\Modules\Blueprints\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Jpeters8889\Architect\Modules\Blueprints\Processors\CreateNewProcessor;

class CreateItemRequest extends FormRequest
{
    public function rules(): array
    {
        return resolve(CreateNewProcessor::class)->validationRules();
    }
}
