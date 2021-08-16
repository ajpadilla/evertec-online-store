<?php

namespace App\Http\Controllers;

use App\Repositories\RepositoryInterface\OrderRepositoryInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerOrderController extends Controller
{
    /** @var OrderRepositoryInterface */
    private $orderRepository;

    /**
     * CustomerOrderController constructor.
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
        $order = $this->orderRepository->getByUserId(Auth::user()->id);
        return view('layouts.orders.customer_order', compact('order'));
    }
}
