<?php

namespace App\Modules\Identity\Http\Requests\Register;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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

            'first_name' => [
                'required',
                'string',
                'max:100'
            ],

            'last_name' => [
                'nullable',
                'string',
                'max:100'
            ],

            'email' => [
                'required',
                'email',
                'max:255'
            ],

            'mobile' => [
                'required',
                'digits:10'
            ],

            'username' => [
                'required',
                'string',
                'min:4',
                'max:50'
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed'
            ],

            'password_confirmation' => [
                'required'
            ],

            'status' => [
                'required',
                'boolean'
            ]

        ];
    }

    /**
     * Custom Validation Messages
     */
    public function messages(): array
    {
        return [

            'first_name.required' => 'First name is required.',

            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',

            'mobile.required' => 'Mobile number is required.',
            'mobile.digits' => 'Mobile number must contain exactly 10 digits.',

            'username.required' => 'Username is required.',

            'password.required' => 'Password is required.',
            'password.min' => 'Password must contain at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',

            'password_confirmation.required' => 'Please confirm your password.',

            'status.required' => 'Status is required.'

        ];
    }

    /**
     * Custom Attribute Names
     */
    public function attributes(): array
    {
        return [

            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email Address',
            'mobile' => 'Mobile Number',
            'username' => 'Username',
            'password' => 'Password',
            'password_confirmation' => 'Confirm Password',
            'status' => 'Status',

        ];
    }
}