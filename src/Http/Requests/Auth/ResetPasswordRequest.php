<?php

namespace App\Modules\Identity\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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

            'token' => [
                'required'
            ],

            'email' => [
                'required',
                'email'
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed'
            ],

            'password_confirmation' => [
                'required'
            ]

        ];
    }

    /**
     * Custom Messages
     */
    public function messages(): array
    {
        return [

            'token.required' => 'Reset token is required.',

            'email.required' => 'Email address is required.',

            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.'

        ];
    }

    /**
     * Custom Attributes
     */
    public function attributes(): array
    {
        return [

            'token' => 'Reset Token',
            'email' => 'Email Address',
            'password' => 'Password'

        ];
    }
}