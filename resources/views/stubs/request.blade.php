@php
    echo "<?php".PHP_EOL;
@endphp

namespace App\Http\Requests\{{ $moduleName }};

use Illuminate\Foundation\Http\FormRequest;

class Create{{ $modelName }}Request extends FormRequest
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
