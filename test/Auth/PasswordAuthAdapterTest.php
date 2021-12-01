<?php

namespace App\Auth;

use App\Service\UserService;
use App\Model\User;
use Laminas\Authentication\Result;
use PHPUnit\Framework\TestCase;

class PasswordAuthAdapterTest extends TestCase
{
    public function testAuthenticateSuccess()
    {
        $userService = new class extends UserService {
            public function __construct()
            {
            }

            public function fetchByCredentials(string $email, string $password): ?User
            {
                return (new User())
                    ->setId(1)
                    ->setName('name')
                    ->setEmail('email');
            }
        };
        $adapter = (new PasswordAuthAdapter($userService))
            ->setCredentials('email', 'password');

        $result = $adapter->authenticate();

        $this->assertEquals(Result::SUCCESS, $result->getCode());
        $this->assertEquals([
            'id' => 1,
            'name' => 'name',
            'email' => 'email'
        ], $result->getIdentity());
    }

    public function testAuthenticateUserNotFoundError()
    {
        $userService = new class extends UserService {
            public function __construct()
            {
            }

            public function fetchByCredentials(string $email, string $password): ?User
            {
                return null;
            }
        };
        $adapter = (new PasswordAuthAdapter($userService))
            ->setCredentials('email', 'password');

        $result = $adapter->authenticate();

        $this->assertEquals(Result::FAILURE_IDENTITY_NOT_FOUND, $result->getCode());
        $this->assertEquals([], $result->getMessages());
    }
}
