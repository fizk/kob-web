<?php
namespace App\Auth;

use App\Service\User;
use Laminas\Authentication\Adapter\AdapterInterface;
use Laminas\Authentication\Result;

class FacebookAuthAdapter implements AdapterInterface
{
    private string $id;
    private string $redirect;
    private string $secret;
    private User $user;
    private string $code;

    public function __construct(User $user, string $id, string $secret, string $redirect)
    {
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
            "https://graph.facebook.com/v8.0/oauth/access_token?",
            "client_id=" . $this->id,
            "&redirect_uri=" . $this->redirect,
            "&client_secret=" . $this->secret,
            "&code=" . $this->code
        ]);

        $access = (array) json_decode(file_get_contents($url));

        $url = implode('', [
            "https://graph.facebook.com/v8.0/me?access_token=",
            $access['access_token'],
            "&fields=last_name%2Cemail%2Cfirst_name%2Cid&method=get",
            "&pretty=0&sdk=joey&suppress_http_code=1"
        ]);

        $response = (array) json_decode(file_get_contents($url));

        $userProperties = $this->user->fetchByEmail($response['email']);
        if ($userProperties) {
            return new Result(Result::SUCCESS, [
                'name' => $userProperties->name,
                'id' => $userProperties->id,
                'email' => $userProperties->email,
            ]);
        }

        return new Result(Result::FAILURE_CREDENTIAL_INVALID, [
            'name' => $this->username
        ]);
    }
}
