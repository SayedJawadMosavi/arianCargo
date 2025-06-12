<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCountryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:countries,name'],
            'kg' => ['required', 'array'],
            'kg.*' => ['required', 'numeric', 'distinct'], // <-- distinct prevents duplicates
            'price' => ['required', 'array'],
            'price.*' => ['required', 'numeric', 'gt:0'], // price must be greater than 0
        ];
    }
}
