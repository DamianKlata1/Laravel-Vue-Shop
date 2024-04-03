<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
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
                'type' => 'required|string|max:45',
                'address' => 'required|string',
                'city' => 'required|string',
                'state' => 'required|string',
                'zipcode' => 'required|string',
                'country_code' => 'required|string|max:3',
            ];

    }
    public function messages()
    {
        return [
            'type.required' => 'The address type is required.',
            'type.string' => 'The address type must be a string.',
            'type.max' => 'The address type may not be greater than :max characters.',
            'address.required' => 'The address is required.',
            'address.string' => 'The address must be a string.',
            'city.required' => 'The city is required.',
            'city.string' => 'The city must be a string.',
            'state.required' => 'The state is required.',
            'state.string' => 'The state must be a string.',
            'zipcode.required' => 'The zipcode is required.',
            'zipcode.string' => 'The zipcode must be a string.',
            'country_code.required' => 'The country code is required.',
            'country_code.string' => 'The country code must be a string.',
            'country_code.size' => 'The country code must be exactly :size characters.',
        ];
    }
}
