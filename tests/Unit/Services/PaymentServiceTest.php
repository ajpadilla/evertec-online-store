<?php

namespace Tests\Unit\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class PaymentServiceTest extends ServiceTest
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_create_request_data_to_place_to_pay_web()
    {
        /** @var array $data */
        $userData = $this->makeNewUserData();

        /** @var User $user */
        $user = $this->userRepository->create($userData);

        /** @var array $data */
        $productData = $this->makeNewProductData();

        /** @var Product $product*/
        $product = $this->productRepository->create($productData);

        /** @var array */
        $data = $this->makeNewOrderData($user,$product);

        /** @var Order $order*/
        $order = $this->orderRepository->create($data);

        $data = $this->paymentService->generateRequestData($order);

        $this->assertArrayHasKey('buyer', $data);
        $this->assertArrayHasKey('payment', $data);
    }
}
