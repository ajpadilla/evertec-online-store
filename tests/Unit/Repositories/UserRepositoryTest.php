<?php

namespace Tests\Unit\Repositories;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends RepositoryTest
{
    public function test_create_a_new_user()
    {
        /** @var array */
        $data = $this->makeNewUserData();

        /** @var User $user */
        $user = $this->userRepository->create($data);

        $this->assertNotNull($user);
    }

    public function test_search_a_exist_user()
    {
        /** @var array */
        $data = $this->makeNewUserData();

        /** @var User $user */
        $user = $this->userRepository->create($data);

        $user = $this->userRepository->getById($user->id);

        $this->assertNotNull($user);
    }

}
