<?php


namespace App\Services\User;


use App\Models\Order;
use App\Models\User;
use App\Repositories\RepositoryInterface\OrderRepositoryInterface;
use App\Repositories\RepositoryInterface\PaymentAttemptRepositoryInterface;
use App\Repositories\RepositoryInterface\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserService
{

    /** @var UserRepositoryInterface  */
    private $userRepository;

    /** @var OrderRepositoryInterface  */
    private $orderRepository;

    /** @var PaymentAttemptRepositoryInterface */
    private $paymentAttemptRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        OrderRepositoryInterface $orderRepository,
        PaymentAttemptRepositoryInterface $paymentAttemptRepository){
        $this->userRepository = $userRepository;
        $this->orderRepository = $orderRepository;
        $this->paymentAttemptRepository = $paymentAttemptRepository;
    }

    /**
     * @param array $inputs
     */
    public function registerNewUser(array $inputs){
        $full_name = $inputs['first_name'].' '.$inputs['last_name'];

        /** @var User $user */
        $user = $this->userRepository->create([
            'name' =>  $full_name,
            'email' =>  $inputs['email'],
            'password' => Hash::make($inputs['password'])
        ]);

        /** @var Order $order */
        $order = $this->orderRepository->create([
            'customer_name' => $user->name,
            'customer_last_name' => $inputs['last_name'],
            'customer_email' => $user->email,
            'customer_mobile' => $inputs['phone'],
            'customer_document_number' => $inputs['document_number'],
            'customer_document_type' => $inputs['document_type'],
            'amount' => 0,
            'status' => 'CREATED',
            'user_id' => $user->id,
        ]);

        $this->paymentAttemptRepository->create([
            'state' => 'INITIAL',
            'order_id' => $order->id
        ]);
    }

}
