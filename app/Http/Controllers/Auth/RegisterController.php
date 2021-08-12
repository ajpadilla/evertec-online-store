<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentAttemptRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    /** @var UserRepository  */
    private $userRepository;

    /** @var OrderRepository  */
    private $orderRepository;

    /** @var PaymentAttemptRepository */
    private $paymentAttemptRepository;

    /**
     * RegisterController constructor.
     * @param UserRepository $userRepository
     * @param OrderRepository $orderRepository
     * @param PaymentAttemptRepository $paymentAttemptRepository
     */
    public function __construct(
        UserRepository $userRepository,
        OrderRepository $orderRepository,
        PaymentAttemptRepository $paymentAttemptRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->orderRepository = $orderRepository;
        $this->paymentAttemptRepository = $paymentAttemptRepository;
    }

    public function show()
    {
        return view('auth.register');
    }

    public function store(StoreUserRequest $request)
    {
        try {
            DB::beginTransaction();

            $full_name = $request->input('first_name').' '.$request->input('last_name');

            /** @var User $user */
            $user = $this->userRepository->create([
                'name' =>  $full_name,
                'email' =>  $request->input('email'),
                'password' => Hash::make($request->input('password'))
            ]);

            /** @var Order $order */
            $order = $this->orderRepository->create([
                'customer_name' => $user->name,
                'customer_last_name' => $request->input('last_name'),
                'customer_email' => $user->email,
                'customer_mobile' => $request->input('phone'),
                'customer_document_number' => $request->input('document_number'),
                'customer_document_type' => $request->input('document_type'),
                'amount' => 0,
                'status' => 'CREATED',
                'user_id' => $user->id,
            ]);

            $this->paymentAttemptRepository->create([
                'state' => 'INITIAL',
                'order_id' => $order->id
            ]);

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            return redirect()->back()->withErrors($exception->getMessage());
        }

        return redirect()->back()->with('alert_success', 'User Register Successful. You can now login');
    }
}
