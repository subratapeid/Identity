<?php

namespace App\Modules\Identity\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
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

            'search' => [
                'nullable',
                'string',
                'max:255'
            ],

            'module' => [
                'nullable',
                'string',
                'max:100'
            ],

            'status' => [
                'nullable',
                'boolean'
            ],

            'sort_by' => [
                'nullable',
                'string'
            ],

            'sort_order' => [
                'nullable',
                'in:asc,desc'
            ],

            'per_page' => [
                'nullable',
                'integer',
                'min:10',
                'max:100'
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