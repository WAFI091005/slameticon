<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $profileId = $this->route('company_profile') ?? $this->route('id');

        return [
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:company_profiles,email,' . $profileId,
            'vision'      => 'nullable|string',
            'mission'     => 'nullable|string',
            'address'     => 'nullable|string',
            'whatsapp'    => 'nullable|string|max:20',
            'logo'        => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'description' => 'nullable|string',
            'ig_url'      => 'nullable|url',
            'tiktok_url'  => 'nullable|url',
            'tagline'     => 'nullable|string|max:255',
        ];
    }
}