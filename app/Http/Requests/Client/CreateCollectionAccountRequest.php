<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class CreateCollectionAccountRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'account_no' => 'required'
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
