<?php

namespace App\Rules;

use App\Repositories\ProductRepository;
use Illuminate\Contracts\Validation\Rule;

class HasProductInStock implements Rule
{
    private ProductRepository $productRepo;

    private string $message;

    public function __construct()
    {
        $this->productRepo = new ProductRepository;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $productId
     * @return bool
     */
    public function passes($attribute, $productId)
    {
        $product = $this->productRepo->findById((int) $productId);

        if(is_null($product)) {
            $this->message = __("The product id is invalid.");
            return false;
        }
        return $product->quantity > 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message ?? __('The product is not available in stock.');
    }
}
