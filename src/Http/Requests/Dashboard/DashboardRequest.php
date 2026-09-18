<?php

namespace App\Modules\Identity\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class DashboardRequest extends FormRequest
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

            'from_date' => [
                'nullable',
                'date'
            ],

            'to_date' => [
                'nullable',
                'date',
                'after_or_equal:from_date'
            ],

            'user_id' => [
                'nullable',
                'integer'
            ],

            'role_id' => [
                'nullable',
                'integer'
            ],

            'status' => [
                'nullable',
                'boolean'
            ],

            'search' => [
                'nullable',
                'string',
                'max:255'
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

    /**
     * Custom Validation Messages
     */
    public function messages(): array
    {
        return [

            'to_date.after_or_equal' => 'The To Date must be greater than or equal to the From Date.',

            'per_page.min' => 'Minimum records per page should be 10.',

            'per_page.max' => 'Maximum records per page should not exceed 100.'

        ];
    }

    /**
     * Custom Attribute Names
     */
    public function attributes(): array
    {
        return [

            'from_date' => 'From Date',
            'to_date' => 'To Date',
            'user_id' => 'User',
            'role_id' => 'Role',
            'status' => 'Status',
            'search' => 'Search',
            'sort_by' => 'Sort By',
            'sort_order' => 'Sort Order',
            'per_page' => 'Records Per Page'

        ];
    }
}