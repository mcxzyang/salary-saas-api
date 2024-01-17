<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class CreateFollowUpRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'customer_id' => 'required',
            'type_id' => 'required',
            'content' => 'required',
            'next_follow_up_at' => 'required|date'
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
