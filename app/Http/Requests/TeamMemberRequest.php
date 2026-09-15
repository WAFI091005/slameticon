<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeamMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH') || $this->isMethod('POST') && $this->route('team_member');

        return [
            'position_id' => 'required|exists:positions,id',
            'name'        => 'required|string|max:255',
            'photo'       => $isUpdate ? 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048' : 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'bio'         => 'nullable|string',
        ];
    }
}