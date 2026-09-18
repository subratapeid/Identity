<?php

namespace App\Modules\Identity\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'name' => [
                'required',
                'string',
                'max:100'
            ],

            'email' => [
                'required',
                'email'
            ],

            'mobile' => [
                'required',
                'digits:10'
            ],

            'role_id' => [
                'required',
                'integer'
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