<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    /** @var int  */
    const PAGINATION = 5;

    /** @var ProductRepository */
    private $productRepository;

    /**
     * HomeController constructor.
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @return Application|Factory|View
     */
    public function dashboard(): View
    {

        $products = $this->productRepository->search([])->paginate(self::PAGINATION);

        return view('layouts.pages.home', compact('products'));
    }
}
