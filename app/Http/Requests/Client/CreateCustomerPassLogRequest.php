<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class CreateCustomerPassLogRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'customer_id' => 'required'
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
