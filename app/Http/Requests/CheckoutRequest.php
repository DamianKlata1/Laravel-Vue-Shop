<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use mysql_xdevapi\Session;

class CheckoutRequest extends FormRequest
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
        return $this->has('address')
            ? [
                'address.type' => 'required|string|max:45',
                'address.address' => 'required|string',
                'address.city' => 'required|string',
                'address.state' => 'required|string',
                'address.zipcode' => 'required|string',
                'address.country_code' => 'required|string|size:3',
            ]
            : [];
    }
    public function messages()
    {
        return [
            'address.type.required' => 'The address type is required.',
            'address.type.string' => 'The address type must be a string.',
            'address.type.max' => 'The address type may not be greater than :max characters.',
            'address.address.required' => 'The address is required.',
            'address.address.string' => 'The address must be a string.',
            'address.city.required' => 'The city is required.',
            'address.city.string' => 'The city must be a string.',
            'address.state.required' => 'The state is required.',
            'address.state.string' => 'The state must be a string.',
            'address.zipcode.required' => 'The zipcode is required.',
            'address.zipcode.string' => 'The zipcode must be a string.',
            'address.country_code.required' => 'The country code is required.',
            'address.country_code.string' => 'The country code must be a string.',
            'address.country_code.size' => 'The country code must be exactly :size characters.',
        ];
    }
}
