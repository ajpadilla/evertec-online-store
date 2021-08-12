<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /** @var ProductRepository */
    private $productRepository;

    /** @var ProductService */
    private $productService;

    public function __construct(ProductService $productService, ProductRepository $productRepository)
    {
        $this->productService = $productService;
        $this->productRepository = $productRepository;
    }

    public function Buy(Request $request, $id)
    {
        /** @var User $user */
        if(!$user = Auth::user()) {
            return redirect()->back()->withErrors("You must create a new account or log in to make the purchase");
        }

        try {
            DB::beginTransaction();

            /** @var Product $products */
            $product = $this->productRepository->find($id);

            /** @var Order $order */
            $order = $this->productService->addProductToOrder($user, $product);

            DB::commit();

            return redirect()->route('customer_order');

        } catch (Exception $exception) {
            DB::rollBack();

            return redirect()->back()->withErrors($exception->getMessage());
        }

    }
}
