<?php

namespace App\Modules\Identity\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class RemoveImageRequest extends FormRequest
{
    /**
     * Determine whether the user is authorized.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation Rules
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Custom Validation Messages
     */
    public function messages(): array
    {
        return [];
    }

    /**
     * Custom Attribute Names
     */
    public function attributes(): array
    {
        return [];
    }
}