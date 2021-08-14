<?php

namespace Tests\Unit\Repositories;

use App\Models\User;
use App\Repositories\RepositoryInterface\UserRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RepositoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @var UserRepositoryInterface */
    protected $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = app(UserRepositoryInterface::class);
    }


    /**
     * @return array
     */
    protected function makeNewUserData(): array
    {
        return[
            'name' =>  $this->faker->lastName,
            'email' => $this->faker->email,
            'password' => Hash::make("{$this->faker->randomNumber()}")
        ];
    }

}
