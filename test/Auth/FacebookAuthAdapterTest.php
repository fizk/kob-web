<?php

namespace App\Auth;

use App\Service\UserService;
use App\Model\User;
use Laminas\Authentication\Result;
use PHPUnit\Framework\TestCase;

class FacebookAuthAdapterTest extends TestCase
{
    public function testAuthenticateSuccess()
    {
        StreamWrapper::$patterns = [
            ['/\/oauth/', '{"access_token": "1234"}'],
            ['/\/me/', '{"email": "e@mail.com"}'],
        ];

        $userService = new class extends UserService {
            public function __construct()
            {
            }

            public function fetchByEmail(string $email): ?User
            {
                return (new User)
                    ->setId(1)
                    ->setName('name')
                    ->setEmail($email)
                ;
            }
        };
        $adapter = (new FacebookAuthAdapter('auth://facebook', $userService, 'is', 'secret', 'redirect'))
            ->setCode('code');

        $result = $adapter->authenticate();

        $this->assertEquals(Result::SUCCESS, $result->getCode());
        $this->assertTrue($result->isValid());
        $this->assertEquals(
            [
                'id' => 1,
                'name' => 'name',
                'email' => 'e@mail.com',
            ],
            $result->getIdentity()
        );
    }

    public function testAuthenticateOauthError()
    {
        StreamWrapper::$patterns = [
            ['/\/oauth/', '{"i_dont_have_access_token": "1234"}'],
            ['/\/me/', '{"email": "e@mail.com"}'],
        ];

        $userService = new class extends UserService {
            public function __construct()
            {
            }

            public function fetchByEmail(string $email): ?User
            {
                return (new User)
                    ->setId(1)
                    ->setName('name')
                    ->setEmail($email)
                ;
            }
        };
        $adapter = (new FacebookAuthAdapter('auth://facebook', $userService, 'is', 'secret', 'redirect'))
            ->setCode('code');

        $result = $adapter->authenticate();

        $this->assertEquals(Result::FAILURE_CREDENTIAL_INVALID, $result->getCode());
        $this->assertEquals(['"access_token" not found in response'], $result->getMessages());
    }

    public function testAuthenticateMeError()
    {
        StreamWrapper::$patterns = [
            ['/\/oauth/', '{"access_token": "1234"}'],
            ['/\/me/', '{"i_dont_have_email": "e@mail.com"}'],
        ];

        $userService = new class extends UserService
        {
            public function __construct()
            {
            }

            public function fetchByEmail(string $email): ?User
            {
                return (new User)
                    ->setId(1)
                    ->setName('name')
                    ->setEmail($email);
            }
        };
        $adapter = (new FacebookAuthAdapter('auth://facebook', $userService, 'is', 'secret', 'redirect'))
        ->setCode('code');

        $result = $adapter->authenticate();

        $this->assertEquals(Result::FAILURE_CREDENTIAL_INVALID, $result->getCode());
        $this->assertEquals(['"email" not found in response'], $result->getMessages());
    }

    public function testAuthenticateUserNotFoundError()
    {
        StreamWrapper::$patterns = [
            ['/\/oauth/', '{"access_token": "1234"}'],
            ['/\/me/', '{"email": "e@mail.com"}'],
        ];

        $userService = new class extends UserService {
            public function __construct()
            {
            }

            public function fetchByEmail(string $email): ?User
            {
                return null;
            }
        };
        $adapter = (new FacebookAuthAdapter('auth://facebook', $userService, 'is', 'secret', 'redirect'))
            ->setCode('code');

        $result = $adapter->authenticate();

        $this->assertEquals(Result::FAILURE_IDENTITY_NOT_FOUND, $result->getCode());
        $this->assertEquals([], $result->getMessages());
    }

    public function setUp(): void
    {
        if (in_array("auth", stream_get_wrappers())) {
            stream_wrapper_unregister("auth");
        }
        stream_wrapper_register('auth', StreamWrapper::class);
    }

    public function tearDown(): void
    {
        if (in_array("auth", stream_get_wrappers())) {
            stream_wrapper_unregister("auth");
        }
    }
}


class StreamWrapper
{
    private string $path;
    private $shouldTerminate = false;
    public static $patterns = [];

    public function stream_eof(): bool
    {
        return $this->shouldTerminate; //
    }

    public function stream_open(
        string $path,
        string $mode,
        int $options,
        ?string &$opened_path
    ): bool
    {
        $this->path = $path;
        return true; //
    }
    public function stream_read(int $count): string
    {
        if ($this->shouldTerminate === false) {
            $this->shouldTerminate = true;
            return $this->selectResult();
        }
        return '';
    }

    public function stream_stat()/*: array|false*/
    {
        return []; //
    }

    private function selectResult()
    {
        foreach(self::$patterns as $route) {
            if (preg_match($route[0], $this->path)) {
                return $route[1];
            }
        }
        return  '';
    }

}
