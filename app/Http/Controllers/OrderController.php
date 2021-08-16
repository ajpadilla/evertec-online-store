<?php

namespace App\Http\Controllers;

use App\Repositories\RepositoryInterface\OrderRepositoryInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /** @var int  */
    const PAGINATION = 5;

    /** @var OrderRepositoryInterface */
    private $orderRepository;

    /**
     * OrderController constructor.
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request): View
    {
        $orders = $this->orderRepository->search([])->paginate(self::PAGINATION);

        return view('layouts.orders.index', compact('orders'));
    }
}
