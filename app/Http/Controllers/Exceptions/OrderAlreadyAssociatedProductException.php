<?php


namespace App\Http\Controllers\Exceptions;

use App\Models\Order;
use Exception;
use Throwable;

class OrderAlreadyAssociatedProductException extends Exception
{
    public function __construct(Order $order)
    {
        $message = "The order number {$order->id} already has an associated product";
        parent::__construct($message);
    }
}
