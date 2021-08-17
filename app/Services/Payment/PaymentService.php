<?php


namespace App\Services\Payment;


use App\Models\Order;
use Carbon\Carbon;

class PaymentService
{
    /**
     * @param Order $order
     * @return array
     */
    public function generateRequestData(Order $order): array
    {

        $reference = 'TEST_' . time();
        $expirationDate = Carbon::now()->addDays(1)->format('c');

        return [
            "buyer" => [
                "name" => "{$order->customer_name}",
                "surname" => "{$order->customer_last_name}",
                "email" => "{$order->customer_email}",
                "document" => "{$order->customer_document_number}",
                "documentType" => "{$order->customer_document_type}",
                "mobile" => $order->customer_mobile
            ],
            "payment" => [
                "reference" => "{$reference}",
                "description" => "Animi hic hic voluptas.",
                "amount" => [
                    "currency" => "COP",
                    "total" => $order->amount
                ]
            ],
            "expiration" => "{$expirationDate}",
            "ipAddress" => env('WEB_CHECKOUT_IP_ADDRESS'),
            "returnUrl" => env('WEB_CHECKOUT_RETURN_SITE'),
            "userAgent" => env('WEB_CHECKOUT_USER_AGENT'),
            "paymentMethod" => null
        ];
    }
}
