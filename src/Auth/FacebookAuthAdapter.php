<?php
namespace App\Auth;

use App\Service\UserService;
use Laminas\Authentication\Adapter\AdapterInterface;
use Laminas\Authentication\Result;

class FacebookAuthAdapter implements AdapterInterface
{
    private string $url;
    private string $id;
    private string $redirect;
    private string $secret;
    private UserService $user;
    private string $code;

    public function __construct(string $url, UserService $user, string $id, string $secret, string $redirect)
    {
        $this->url = $url;
        $this->user = $user;
        $this->id = $id;
        $this->redirect = $redirect;
        $this->secret = $secret;
    }

    public function setCode(string $code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Performs an authentication attempt
     *
     * @return Result
     */
    public function authenticate()
    {
        $url = implode('', [
            "{$this->url}/oauth/access_token?",
            "client_id=" . $this->id,
            "&redirect_uri=" . $this->redirect,
            "&client_secret=" . $this->secret,
            "&code=" . $this->code
        ]);

        $access = (array) json_decode(file_get_contents($url));

        if (!array_key_exists('access_token', $access)) {
            return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, ['"access_token" not found in response']);
        }

        $url = implode('', [
            "{$this->url}/me?access_token=",
            $access['access_token'],
            "&fields=last_name%2Cemail%2Cfirst_name%2Cid&method=get",
            "&pretty=0&sdk=joey&suppress_http_code=1"
        ]);

        $response = (array) json_decode(file_get_contents($url));

        if (!array_key_exists('email', $response)) {
            return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, ['"email" not found in response']);
        }

        $userProperties = $this->user->fetchByEmail($response['email']);
        if ($userProperties) {
            return new Result(Result::SUCCESS, [
                'name' => $userProperties->getName(),
                'id' => $userProperties->getId(),
                'email' => $userProperties->getEmail(),
            ]);
        }

        return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, null, []);
    }
}
