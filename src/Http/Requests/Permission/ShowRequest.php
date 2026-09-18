<?php

namespace App\Modules\Identity\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;

class ShowRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
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