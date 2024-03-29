<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users|email|max:255',
            'phone' => 'required',
            'document_type' => 'required',
            'document_number' => 'required|unique:orders,customer_document_number',
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required|min:6'
        ];
    }
}
