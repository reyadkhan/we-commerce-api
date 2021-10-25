<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('sanctum')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:200|unique:products,name,' . $this->route('id'),
            'price' => 'required|numeric|min:0|max:99999999.99',
            'qty' => 'required|numeric|min:0|max:4294967295',
            'desc' => 'nullable|string|max:20000',
            'img' => 'nullable|string|max:255'
        ];
    }
}
