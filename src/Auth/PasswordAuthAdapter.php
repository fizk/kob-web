<?php
namespace App\Auth;

use App\Service\UserService;
use Laminas\Authentication\Adapter\AdapterInterface;
use Laminas\Authentication\Result;

class PasswordAuthAdapter implements AdapterInterface
{
    private UserService $user;
    private string $email;
    private string $password;

    public function __construct(UserService $user)
    {
        $this->user = $user;
    }

    public function setCredentials(string $email, string $password): self
    {
        $this->email = $email;
        $this->password = $password;
        return $this;
    }

    /**
     * Performs an authentication attempt
     *
     * @return Result
     */
    public function authenticate()
    {
        $userProperties = $this->user->fetchByCredentials($this->email, $this->password);
        if ($userProperties) {
            return new Result(Result::SUCCESS, [
                'name' => $userProperties->getName(),
                'id' => $userProperties->getId(),
                'email' => $userProperties->getEmail(),
            ]);
        }

        return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, []);
    }
}
