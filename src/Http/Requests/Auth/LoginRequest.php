<?php

namespace App\Modules\Identity\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
        return [

            'email' => [
                'required',
                'email',
                'max:255'
            ],

            'password' => [
                'required',
                'string',
                'min:8'
            ],

            'remember' => [
                'nullable',
                'boolean'
            ]

        ];
    }

    /**
     * Custom Messages
     */
    public function messages(): array
    {
        return [

            'email.required' => 'Email address is required.',
            'email.email' => 'Enter a valid email address.',

            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.'

        ];
    }

    /**
     * Custom Attributes
     */
    public function attributes(): array
    {
        return [

            'email' => 'Email Address',
            'password' => 'Password'

        ];
    }
}