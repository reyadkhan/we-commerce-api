<?php

namespace App\Exceptions;

use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StockIsNotAvailableException extends Exception
{
    public function __construct(Product $product, int $quantity)
    {
        $message = "The ordered quantity [{$quantity}] is not available for the product '{$product->name}'";
        parent::__construct($message, 422);
    }

    public function render(Request $request)
    {
        if($request->is('api/*')) {
            return response()->json(['message' => $this->message], $this->code);
        }
    }

    public function report()
    {
        Log::error($this->message);
    }
}
