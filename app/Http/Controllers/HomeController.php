<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepository;

class HomeController extends Controller
{
    const PAGINATION = 5;

    /** @var ProductRepository */
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function dashboard()
    {

        $products = $this->productRepository->search([])->paginate(self::PAGINATION);

        return view('layouts.pages.home', compact('products'));
    }
}
