<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules() {
      return [
        'name' => 'required|min:3|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
        'role' => 'required|in:' . \App\Enums\Role::SELLER->value . ',' . \App\Enums\Role::BUYER->value,
        'buyer_type' => 'nullable|integer|in:' . \App\Enums\BuyerType::PRIVATE->value . ',' . \App\Enums\BuyerType::COMPANY->value,
      ];
    }

    public function messages() {
      return [
        'name.required' => 'Mandatory name',
        'name.min' => 'Name must have at least 3 characters',
        'email.email' => 'Invalid email.',
        'email.unique' => 'Email already exists.',
      ];
    }
}
