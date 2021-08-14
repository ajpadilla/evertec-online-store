<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Exceptions\OrderAlreadyAssociatedProductException;
use App\Http\Controllers\Exceptions\OrderAssociatedWithoutUserException;
use App\Http\Requests\BuyProductRequest;
use App\Models\Product;
use App\Models\User;
use App\Repositories\ProductRepository;
use App\Services\Product\ProductService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use PDOException;

class ProductController extends Controller
{
    /** @var ProductRepository */
    private $productRepository;

    /** @var ProductService */
    private $productService;

    /**
     * ProductController constructor.
     * @param ProductService $productService
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductService $productService, ProductRepository $productRepository)
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
        /** @var User $user */
        if(!$user = Auth::user()) {
            return redirect()->back()->withErrors("You must create a new account or log in to make the purchase");
        }

        try {
            DB::beginTransaction();

            /** @var Product $products */
            $product = $this->productRepository->find($id);

            $this->productService->addProductToOrder($user, $product);

            DB::commit();

            return redirect()->route('customer_order');

        } catch (ModelNotFoundException | QueryException | PDOException | OrderAssociatedWithoutUserException | OrderAlreadyAssociatedProductException $exception) {
            DB::rollBack();
            logger($exception->getMessage());
            logger($exception->getTraceAsString());

            return redirect()->back()->withErrors($exception->getMessage());
        }
    }
}
