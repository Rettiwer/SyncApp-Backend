<?php
/**
 * Created by PhpStorm.
 * User: rettiwer
 * Date: 29.07.2019
 * Time: 23:13
 */

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RepoController
{
    private $repoDao;
    private $responseProvider;

    function __construct($app)
    {
        $this->repoDao = new \App\Dao\RepoDao($app->db);
        $this->responseProvider = $app->ResponseProvider;
    }

    function getRepo(Request $request, Response $response, $args) {
        $repo_id = $args["repo_id"];

        $repo = $this->repoDao->selectRepoById($repo_id);
        if (empty($repo))
            return $this->responseProvider->withError($response, "REPO_DOES_NOT_EXISTS", 401);

        $data = array(
            "remote_dir" => $repo["directory"],
            "locker" => $repo["locker"],
        );

        return $this->responseProvider->withOkData($response, 'FOUND_REPO', $data);
    }

    function lockRepo(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $repo_id = $args["repo_id"];
        $user_id = $data["user_id"];

        $repo = $this->repoDao->selectRepoById($repo_id);
        if (empty($repo))
            return $this->responseProvider->withError($response, "REPO_DOES_NOT_EXISTS", 401);

        $this->repoDao->updateUserLock($user_id, $repo_id);

        if($user_id == "0")
            return $this->responseProvider->withOk($response, 'REPO_IS_UNLOCKED');
        else
            return $this->responseProvider->withOk($response, 'REPO_IS_LOCKED');
    }
}