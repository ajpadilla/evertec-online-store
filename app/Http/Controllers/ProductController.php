<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Exceptions\OrderAlreadyAssociatedProductException;
use App\Http\Controllers\Exceptions\UserWithoutAssociatedOrder;
use App\Http\Requests\BuyProductRequest;
use App\Repositories\RepositoryInterface\ProductRepositoryInterface;
use App\Services\Product\ProductService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use PDOException;

class ProductController extends Controller
{
    /** @var ProductRepositoryInterface */
    private $productRepository;

    /** @var ProductService */
    private $productService;

    /**
     * ProductController constructor.
     * @param ProductService $productService
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductService $productService, ProductRepositoryInterface $productRepository)
    {
        $this->productService = $productService;
        $this->productRepository = $productRepository;
    }

    /**
     * @param BuyProductRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function Buy(BuyProductRequest $request, $id): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $this->productService->addProductToOrder($request->getUser(), $request->getProduct());

            DB::commit();

            return redirect()->route('customer_order');

        } catch (ModelNotFoundException | QueryException | PDOException | UserWithoutAssociatedOrder | OrderAlreadyAssociatedProductException $exception) {
            DB::rollBack();
            logger($exception->getMessage());
            logger($exception->getTraceAsString());

            return redirect()->back()->withErrors($exception->getMessage());
        }
    }
}
