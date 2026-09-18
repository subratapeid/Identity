<?php

namespace App\Modules\Identity\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'search' => [
                'nullable',
                'string',
                'max:255'
            ],

            'status' => [
                'nullable',
                'in:active,inactive'
            ],

            'role_id' => [
                'nullable',
                'integer'
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