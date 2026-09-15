<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'settings'         => 'required_without:key|array',
            'settings.*.key'   => 'required_with:settings|string|max:255',
            'settings.*.value' => 'nullable|string',
            'key'              => 'required_without:settings|string|max:255',
            'value'            => 'nullable|string',
        ];
    }
}