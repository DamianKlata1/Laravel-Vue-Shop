<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Product;
use Illuminate\Validation\Validator;

class CartStoreRequest extends FormRequest
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
            'quantity' => 'integer|min:1',

        ];
    }
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $product = Product::find($this->route('product'))->first();

            if ($this->quantity && $this->quantity > $product->quantity) {
                $validator->errors()->add('quantity', 'Not enough stock!');
            }

            if ($product->published === 0) {
                $validator->errors()->add('published', 'Product is not available!');
            }
        });
    }
}
