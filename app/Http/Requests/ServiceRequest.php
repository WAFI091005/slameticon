<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'         => 'required|string|max:255',
            'icon'          => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'description'   => 'nullable|string',
            'display_order' => 'nullable|integer',
        ];
    }
}