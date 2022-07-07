<?php

namespace Jpeters8889\Architect\Modules\Blueprints\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Jpeters8889\Architect\Modules\Blueprints\Processors\EditItemProcessor;

class UpdateItemRequest extends FormRequest
{
    public function rules(): array
    {
        return resolve(EditItemProcessor::class)->validationRules();
    }
}
