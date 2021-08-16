<?php

namespace Tests\Unit\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Repositories\RepositoryInterface\OrderRepositoryInterface;
use App\Repositories\RepositoryInterface\ProductRepositoryInterface;
use App\Repositories\RepositoryInterface\UserRepositoryInterface;
use App\Services\Payment\PaymentService;
use App\Services\Placetopay\WebCheckout\PlaceToPayWebCheckoutService;
use App\Services\Product\ProductService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @var PlaceToPayWebCheckoutService */
    protected $placeToPayWebCheckoutService;

    /** @var ProductService */
    protected $productService;

    /** @var PaymentService */
    protected $paymentService;

    /** @var UserRepositoryInterface */
    protected $userRepository;

    /** @var ProductRepositoryInterface */
    protected $productRepository;

    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->placeToPayWebCheckoutService = app(PlaceToPayWebCheckoutService::class);
        $this->userRepository = app(UserRepositoryInterface::class);
        $this->productRepository = app(ProductRepositoryInterface::class);
        $this->orderRepository = app(OrderRepositoryInterface::class);
        $this->productService = app(ProductService::class);
        $this->paymentService = app(PaymentService::class);
    }

    /**
     * @param Order $order
     * @return array
     */
    protected function placeToPayWebCheckoutData(Order $order): array
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
            "paymentMethod" => null,
        ];
    }

    /**
     * @return array
     */
    protected function makeNewUserData(): array
    {
        return[
            'name' =>  $this->faker->lastName,
            'email' => $this->faker->email,
            'password' => Hash::make("{$this->faker->randomNumber()}")
        ];
    }

    /**
     * @return array
     */
    protected function makeNewProductData(): array
    {
        return [
            'name' => $this->faker->text($maxNbChars = 10),
            'price' =>  $this->faker->numberBetween($min = 10000, $max = 20000)
        ];
    }

    /**
     * @param User $user
     * @param Product $product
     * @return array
     */
    protected function makeNewOrderData(User $user, Product $product): array
    {
        return [
            'customer_name' => $this->faker->firstName,
            'customer_last_name' => $this->faker->lastName,
            'customer_email' => $this->faker->email,
            'customer_mobile' => $this->faker->phoneNumber,
            'customer_document_number' => '1090538589',
            'customer_document_type' => 'CC',
            'amount' => $product->price,
            'status' => 'CREATED',
            'user_id' => $user->id,
            'product_id' => $product->id
        ];
    }

    /**
     * @param User $user
     * @return array
     */
    protected function createNewOrderDataWithoutAssociatedProduct(User $user): array
    {
        return [
            'customer_name' => $this->faker->firstName,
            'customer_last_name' => $this->faker->lastName,
            'customer_email' => $this->faker->email,
            'customer_mobile' => $this->faker->phoneNumber,
            'customer_document_number' => '1090538589',
            'customer_document_type' => 'CC',
            'amount' => 0,
            'status' => 'CREATED',
            'user_id' => $user->id,
            'product_id' => null
        ];
    }
}
