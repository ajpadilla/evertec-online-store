<?php

namespace Tests\Unit\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use GuzzleHttp\Exception\GuzzleException;

class PlaceToPayWebCheckoutServiceTest extends ServiceTest
{
    /**
     * @throws GuzzleException
     */
    public function test_create_successful_request_to_place_to_pay_web()
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

        $data = $this->placetopayWebCheckoutData($order);

        $response = $this->placeToPayWebCheckoutService->createRequest($data);

        $this->assertEquals('OK',$response->status->status);
    }

    /**
     * @throws GuzzleException
     */
    public function test_get_existing_request_information_to_place_to_pay_web()
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

        $data = $this->placetopayWebCheckoutData($order);

        $response = $this->placeToPayWebCheckoutService->createRequest($data);

        $responseData = $this->placeToPayWebCheckoutService->getRequestInformation($response->requestId);

        $this->assertEquals('PENDING',$responseData->status->status);
    }
}
