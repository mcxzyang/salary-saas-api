<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CreateCompanyOptionRequest extends FormRequest
{
    public function rules(): array
    {
        return [

        ];
    }

    public function messages(): array
    {
        return [

        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
