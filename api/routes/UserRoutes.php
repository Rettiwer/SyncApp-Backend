<?php
/**
 * Created by PhpStorm.
 * User: rettiwer
 * Date: 30.07.2019
 * Time: 00:31
 */

use App\Utilities\JwtUtilities;

$app->group('/api', function () {
    $this->get('/user/{id}', App\Controllers\UserController::class . ':getUser');
})->add(function ($request, $response, $next) {
    $token = $request->getHeader("Authorization")[0];
    if ($token == null)
        return $this->ResponseProvider->withError($response, 'No token!', 401);
    if (!JwtUtilities::validateToken($token))
        return $this->ResponseProvider->withError($response, 'Invalid token!', 401);
    return $next($request, $response);
});