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

class FileController
{
    private $repoDao;
    private $responseProvider;

    function __construct($app)
    {
        $this->repoDao = new \App\Dao\RepoDao($app->db);
        $this->responseProvider = $app->ResponseProvider;
    }

    public function renameFile(Request $request, Response $response, $args) {
        $repoId = $args["repo_id"];

        $repo = $this->repoDao->selectRepoById($repoId);

        if (empty($repo))
            return $this->responseProvider->withError($response, 'REPO_DOES_NOT_EXISTS', 404);

        $data = $request->getParsedBody();
        $oldFileName = $repo["directory"] . '/' . $data["old_file_name"];
        $newFileName = $repo["directory"] . '/' . $data["new_file_name"];

        if(!rename($oldFileName , $newFileName))
            return $this->responseProvider->withError($response, 'UNEXPECTED_ERROR_WHEN_RENAMING', 404);

        return $this->responseProvider->withOk($response, 'Filename changed!');
    }
}