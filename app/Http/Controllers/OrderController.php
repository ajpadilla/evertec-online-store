<?php

namespace App\Http\Controllers;

use App\Repositories\OrderRepository;
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

    public function index(Request $request)
    {
        $orders = $this->orderRepository->search([])->paginate(5);

        return view('layouts.orders.index', compact('orders'));
    }
}
