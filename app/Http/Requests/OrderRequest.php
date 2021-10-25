<?php

namespace App\Http\Requests;

use App\Rules\HasProductInStock;
use App\Rules\HasProductQtyInStock;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'products' => 'required|array|min:1',
            'products.*.id' => ['required', 'numeric', new HasProductInStock],
            'products.*.quantity' => 'required|integer|min:1'
        ];
    }
}
