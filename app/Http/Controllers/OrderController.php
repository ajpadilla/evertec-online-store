<?php

namespace App\Http\Controllers;

use App\Repositories\OrderRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /** @var OrderRepository */
    private $orderRepository;

    /**
     * OrderController constructor.
     * @param OrderRepository $orderRepository
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request): View
    {
        $orders = $this->orderRepository->search([])->paginate(5);

        return view('layouts.orders.index', compact('orders'));
    }
}
