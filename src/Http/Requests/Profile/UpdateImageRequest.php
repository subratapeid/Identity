<?php

namespace App\Modules\Identity\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateImageRequest extends FormRequest
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

            'profile_image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ]

        ];
    }

    /**
     * Custom Validation Messages
     */
    public function messages(): array
    {
        return [

            'profile_image.required' => 'Profile image is required.',
            'profile_image.image' => 'Please upload a valid image.',
            'profile_image.mimes' => 'Allowed formats: JPG, JPEG, PNG and WEBP.',
            'profile_image.max' => 'Maximum image size is 2 MB.'

        ];
    }

    /**
     * Custom Attribute Names
     */
    public function attributes(): array
    {
        return [

            'profile_image' => 'Profile Image'

        ];
    }
}