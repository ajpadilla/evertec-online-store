<?php


namespace App\Services\Product;


use App\Http\Controllers\Exceptions\OrderAlreadyAssociatedProductException;
use App\Http\Controllers\Exceptions\UserWithoutAssociatedOrder;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Repositories\RepositoryInterface\OrderRepositoryInterface;
use App\Repositories\RepositoryInterface\ProductRepositoryInterface;
use Exception;

class ProductService
{
    /** @var OrderRepositoryInterface */
    private $orderRepository;

    /** @var ProductRepositoryInterface */
    private $productRepository;

    /**
     * ProductService constructor.
     * @param ProductRepositoryInterface $productRepository
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository, OrderRepositoryInterface $orderRepository)
    {
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param User $user
     * @param Product $product
     * @return Order
     * @throws OrderAlreadyAssociatedProductException
     * @throws UserWithoutAssociatedOrder
     */
    public function addProductToOrder(User $user, Product $product): Order
    {
        /** @var Order $order */
        if(!$order = $this->orderRepository->getByUserId($user->id)){
            throw new UserWithoutAssociatedOrder($user);
        }

        if ($order->getTotalProducts() > 0){
            throw new OrderAlreadyAssociatedProductException($order);
        }

        $this->orderRepository->associateProduct($order, $product);

        return $order;
    }

}
