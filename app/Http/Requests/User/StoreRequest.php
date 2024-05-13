<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class StoreRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|min:5|max:500',
            'email' => 'required|min:5|max:500|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::default()
                ::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()
            ],
            'password_confirmation' => 'required'
        ];
    }
}
