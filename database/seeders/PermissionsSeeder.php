<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use App\Repositories\RepositoryInterface\OrderRepositoryInterface;
use App\Repositories\RepositoryInterface\PaymentAttemptRepositoryInterface;
use App\Repositories\RepositoryInterface\UserRepositoryInterface;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Faker\Generator;
use Illuminate\Container\Container;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    private $faker;

    public function __construct()
    {
        $this->faker = $this->withFaker();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /** @var UserRepositoryInterface*/
        $userRepository = app(UserRepositoryInterface::class);

        /** @var OrderRepositoryInterface*/
        $orderRepository = app(OrderRepositoryInterface::class);

        /** @var PaymentAttemptRepositoryInterface*/
        $paymentAttemptRepository = app(PaymentAttemptRepositoryInterface::class);

        //Permission list
        Permission::create(['name' => 'list.all.orders']);

        //Admin
        $admin = Role::create(['name' => 'Admin']);

        $admin->givePermissionTo([
            'list.all.orders',
        ]);

        /** @var User $user */
        $user = $userRepository->create([
            'name' => 'Admin',
            'email' => 'Admin@example.com',
            'password' => Hash::make('123456')
        ]);

        $user->assignRole('Admin');

        /** @var Order $order */
        $order = $orderRepository->create([
            'customer_name' => $this->faker->firstName,
            'customer_last_name' => $this->faker->lastName,
            'customer_email' => $this->faker->email,
            'customer_mobile' => $this->faker->phoneNumber,
            'customer_document_number' => '1090538589',
            'customer_document_type' => 'CC',
            'amount' => 0,
            'status' => 'CREATED',
            'user_id' => $user->id,
        ]);

        $paymentAttemptRepository->create([
            'state' => 'INITIAL',
            'order_id' => $order->id
        ]);


    }

    /**
     * @return object
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function withFaker()
    {
        return Container::getInstance()->make(Generator::class);
    }
}
