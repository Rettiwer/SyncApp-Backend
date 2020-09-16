<?php
/**
 * Created by PhpStorm.
 * User: rettiwer
 * Date: 30.07.2019
 * Time: 00:31
 */

$app->group('/api', function () {
    $this->get('/commit/{repo_id}', App\Controllers\CommitController::class . ':getLastCommmit');
    $this->put('/commit', App\Controllers\CommitController::class . ':updateCommit');
})->add(function ($request, $response, $next) {
    $token = $request->getHeader("Authorization")[0];
    if ($token == null)
        return $this->ResponseProvider->withError($response, 'EMPTY_TOKEN', 401);
    return $next($request, $response);
});