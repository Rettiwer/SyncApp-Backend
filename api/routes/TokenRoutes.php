<?php
/**
 * Created by PhpStorm.
 * User: rettiwer
 * Date: 25.04.2019
 * Time: 05:38
 */

$app->group('/api', function () {
    $this->post('/token', App\Controllers\TokenController::class . ':createToken');
});