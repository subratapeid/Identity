<?php

namespace App\Modules\Identity\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LogoutRequest extends FormRequest
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
     * Custom Messages
     */
    public function messages(): array
    {
        return [];
    }

    /**
     * Custom Attributes
     */
    public function attributes(): array
    {
        return [];
    }
}