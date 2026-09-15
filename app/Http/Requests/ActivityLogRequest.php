<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivityLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search'    => 'nullable|string',
            'action'    => 'nullable|string',
            'user_id'   => 'nullable|uuid',
            'per_page'  => 'nullable|integer|min:1|max:100',
        ];
    }
}