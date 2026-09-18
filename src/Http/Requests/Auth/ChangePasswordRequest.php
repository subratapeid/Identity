<?php

namespace App\Modules\Identity\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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

            'current_password' => [
                'required'
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

            'current_password.required' => 'Current password is required.',

            'password.required' => 'New password is required.',
            'password.confirmed' => 'Password confirmation does not match.'

        ];
    }

    /**
     * Custom Attributes
     */
    public function attributes(): array
    {
        return [

            'current_password' => 'Current Password',
            'password' => 'New Password',
            'password_confirmation' => 'Confirm Password'

        ];
    }
}