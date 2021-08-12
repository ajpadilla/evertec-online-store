<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /** @var ProductRepository */
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function dashboard()
    {
        $products = $this->productRepository->search([])->paginate(5);

        return view('layouts.pages.home', compact('products'));
    }
}
