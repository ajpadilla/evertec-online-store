<?php

namespace Tests\Unit\Repositories;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class OrderRepositoryTest extends RepositoryTest
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_create_a_new_order()
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

        $this->assertNotNull($order);
    }

    public function test_search_a_exist_order()
    {
        /** @var array $data */
        $userData = $this->makeNewUserData();

        /** @var User $user */
        $user = $this->userRepository->create($userData);

        /** @var array $data */
        $productData = $this->makeNewProductData();

        /** @var Product $product*/
        $product = $this->productRepository->create($productData);

        /** @var array $data */
        $data = $this->makeNewOrderData($user,$product);

        /** @var Order $order*/
        $order = $this->orderRepository->create($data);

        /** @var Order $order */
        $order = $this->orderRepository->find($order->id);

        $this->assertNotNull($order);
    }
}
