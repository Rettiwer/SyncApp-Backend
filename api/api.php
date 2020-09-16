<?php

require '../vendor/autoload.php';

//$config['displayErrorDetails'] = true;
$config['db']['host']   = "127.0.0.1";
$config['db']['user']   = "sync";
$config['db']['pass']   = "123456";
$config['db']['dbname'] = "sync_db";

$app = new \Slim\App(["settings" => $config]);


$container = $app->getContainer();

$container['phpErrorHandler'] = function ($container) {
        return function ($request, $response, $exception) use ($container) {
            return $response->withStatus(500)
                ->withHeader('Content-Type', 'text/html')
                ->write('Something went wrong!' . $exception);
        };
    };

$container['ResponseProvider'] = function() {
    return new \App\Utilities\ResponseProvider();
};

$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    try{
        $pdo = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'],
            $db['user'], $db['pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    }
    catch(PDOException $ex){
        return $ex->getMessage();
    }
};

$container['\UserController'] = function($app) {
    return new App\Controllers\UserController($app);
};
$container['\TokenController'] = function($app) {
    return new App\Controllers\TokenController($app);
};
$container['\RepoController'] = function($app) {
    return new App\Controllers\RepoController($app);
};
$container['\CommitController'] = function($app) {
    return new App\Controllers\CommitController($app);
};
$container['\FileController'] = function($app) {
    return new App\Controllers\FileController($app);
};

$app->add(function ($request, $response, $next) {
    if($request->isPost()) {
        if (strpos($request->getContentType(), "application/json"))
            die("To nie json " . $request->getContentType());
    }
    return $next($request, $response);
});

require_once 'routes/UserRoutes.php';
require_once 'routes/RepoRoutes.php';
require_once 'routes/CommitRoutes.php';
require_once 'routes/TokenRoutes.php';

$app->run();