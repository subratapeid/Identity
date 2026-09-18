<?php

namespace App\Modules\Identity\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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

            'date_of_birth' => [
                'nullable',
                'date'
            ],

            'gender' => [
                'nullable',
                'in:male,female,other'
            ],

            'address' => [
                'nullable',
                'string',
                'max:500'
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
            'mobile.digits' => 'Mobile number must contain exactly 10 digits.'

        ];
    }

    /**
     * Custom Attribute Names
     */
    public function attributes(): array
    {
        return [

            'first_name'    => 'First Name',
            'last_name'     => 'Last Name',
            'email'         => 'Email Address',
            'mobile'        => 'Mobile Number',
            'date_of_birth' => 'Date of Birth',
            'gender'        => 'Gender',
            'address'       => 'Address'

        ];
    }
}