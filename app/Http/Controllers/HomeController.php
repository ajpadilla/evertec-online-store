<?php

namespace App\Http\Controllers;

use App\Repositories\RepositoryInterface\ProductRepositoryInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    /** @var int  */
    const PAGINATION = 5;

    /** @var ProductRepositoryInterface */
    private $productRepository;

    /**
     * HomeController constructor.
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
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
