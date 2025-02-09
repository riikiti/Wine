<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|unique:users,phone',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'email' => 'nullable|unique:users,email',
            'password' => 'nullable|string|min:6',
            'wine_ids' => 'array',
            'wine_ids.*' => 'exists:wines,id',
        ];
    }
}
