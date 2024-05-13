<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class PutRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|min:3|max:500'
        ];
    }
}
