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

class CommitController
{
    private $commitDao;
    private $repoDao;
    private $responseProvider;

    function __construct($app)
    {
        $this->commitDao = new \App\Dao\CommitDao($app->db);
        $this->repoDao = new \App\Dao\RepoDao($app->db);
        $this->responseProvider = $app->ResponseProvider;
    }

    function getLastCommmit(Request $request, Response $response, $args) {
        $repo_id = $args["repo_id"];

        $commit = $this->commitDao->selectCommitByRepoId($repo_id);
        if (empty($commit))
            return $this->responseProvider->withError($response, "REPO_DOES_NOT_HAVE_ANY_COMMITS", 401);

        return $this->responseProvider->withOkData($response, 'Found last commit', $commit);
    }

    function updateCommit(Request $request, Response $response, $args) {
        $data = $request->getParsedBody();
        $repo_id = $data["repo_id"];
        $commit_id = $data["commit_id"];
        $created_at = $data["created_at"];

        $repo = $this->repoDao->selectRepoById($repo_id);
        if (empty($repo))
            return $this->responseProvider->withError($response, "REPO_DOES_NOT_EXISTS", 401);

        $commit = $this->commitDao->selectCommitByRepoId($repo_id);
        if(!empty($commit))
            $this->commitDao->updateCommit($repo_id, $commit_id, $created_at);
        else
            $this->commitDao->insertCommit($repo_id, $commit_id, $created_at);

        return $this->responseProvider->withOk($response, 'COMMIT_UPDATED');
    }
}