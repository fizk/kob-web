<?php
namespace App\Auth;

use App\Service\User;
use Laminas\Authentication\Adapter\AdapterInterface;
use Laminas\Authentication\Result;

class PasswordAuthAdapter implements AdapterInterface
{
    private User $user;
    private string $username;
    private string $password;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function setCredentials(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Performs an authentication attempt
     *
     * @return Result
     */
    public function authenticate()
    {
        $userProperties = $this->user->fetchByCredentials($this->username, $this->password);
        if ($userProperties) {
            return new Result(Result::SUCCESS, [
                'name' => $userProperties->name,
                'id' => $userProperties->id,
                'email' => 'my@email.com',// 'email' => $userProperties->email,
            ]);
        }

        return new Result(Result::FAILURE_CREDENTIAL_INVALID, [
            'name' => $this->username
        ]);
    }
}
