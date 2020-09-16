<?php
/**
 * Created by PhpStorm.
 * User: rettiwer
 * Date: 30.07.2019
 * Time: 00:31
 */

$app->group('/api', function () {
    $this->get('/repo/{repo_id}', App\Controllers\RepoController::class . ':getRepo');

    $this->post('/repo/{repo_id}/lock', App\Controllers\RepoController::class . ':lockRepo');

    $this->put('/repo/{repo_id}/file', App\Controllers\FileController::class . ':renameFile');
})->add(function ($request, $response, $next) {
    $token = $request->getHeader("Authorization")[0];
    if ($token == null)
        return $this->ResponseProvider->withError($response, 'EMPTY_TOKEN', 401);
    return $next($request, $response);
});