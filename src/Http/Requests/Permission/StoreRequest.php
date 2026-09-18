<?php

namespace App\Modules\Identity\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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

            'name' => [
                'required',
                'string',
                'max:100'
            ],

            'display_name' => [
                'nullable',
                'string',
                'max:100'
            ],

            'module' => [
                'required',
                'string',
                'max:100'
            ],

            'description' => [
                'nullable',
                'string',
                'max:500'
            ],

            'status' => [
                'required',
                'boolean'
            ]

        ];
    }

    public function messages(): array
    {
        return [];
    }

    public function attributes(): array
    {
        return [];
    }
}