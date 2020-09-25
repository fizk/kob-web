<?php
namespace App\Auth;

use App\Service;
use Laminas\Authentication\Adapter\AdapterInterface;
use Laminas\Authentication\Result;

class SimpleAuthAdapter implements AdapterInterface
{
    private $password;
    private $username;

    /** @var \App\Service\User */
    private $user;

    public function __construct(Service\User $user)
    {
        $this->user = $user;
    }

    public function setPassword(string $password) : void
    {
        $this->password = $password;
    }

    public function setUsername(string $username) : void
    {
        $this->username = $username;
    }

    /**
     * Performs an authentication attempt
     *
     * @return Result
     */
    public function authenticate()
    {
        $userProperties = $this->user->fetch($this->username, $this->password);
        // Retrieve the user's information (e.g. from a database)
        // and store the result in $row (e.g. associative array).
        // If you do something like this, always store the passwords using the
        // PHP password_hash() function!
        if ($userProperties) {
            return new Result(Result::SUCCESS, $userProperties);
        }

        return new Result(Result::FAILURE_CREDENTIAL_INVALID, $this->username);
    }
}
