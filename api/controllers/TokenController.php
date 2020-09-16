<?php
/**
 * Created by PhpStorm.
 * User: rettiwer
 * Date: 29.07.2019
 * Time: 22:15
 */
namespace App\Controllers;

use App\Utilities\JwtUtilities;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TokenController
{
    private $userDao;
    private $sessionDao;
    private $responseProvider;

    function __construct($app)
    {
        $this->userDao = new \App\Dao\UserDao($app->db);
        $this->sessionDao = new \App\Dao\SessionDao($app->db);
        $this->responseProvider = $app->ResponseProvider;
    }

    public function createToken(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $email = $data["email"];
        $password = $data["password"];

        $user = $this->userDao->selectUserByEmail($email);
        if (empty($user))
            return $this->responseProvider->withError($response, "USER_DOES_NOT_EXISTS", 401);

        if (!password_verify($password, $user["password"]))
            return $this->responseProvider->withError($response, "INVALID_PASSWORD", 401);

        if(!$this->sessionDao->selectSession($user["id"]))
            $this->sessionDao->insertSession($user["id"]);
        else
            $this->sessionDao->updateSession($user["id"]);

        $token = JwtUtilities::generateNewToken($user["id"]);

        $tokens = array(
            "key" => $token
        );

        return $this->responseProvider->withOkData($response, 'New token generated!', $tokens);
    }
}
