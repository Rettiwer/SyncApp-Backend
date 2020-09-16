<?php
/**
 * Created by PhpStorm.
 * User: rettiwer
 * Date: 30.07.2019
 * Time: 00:20
 */

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController
{
    private $userDao;
    private $responseProvider;

    function __construct($app)
    {
        $this->userDao = new \App\Dao\UserDao($app->db);
        $this->responseProvider = $app->ResponseProvider;
    }

    public function getUser(Request $request, Response $response, $args) {
        $id = $args['id'];

        $user = $this->userDao->selectUserById($id);

        if (empty($user))
            return $this->responseProvider->withError($response, 'USER_DOES_NOT_EXISTS', 404);


        return $this->responseProvider->withOkData($response, 'User found!', $user);
    }
}