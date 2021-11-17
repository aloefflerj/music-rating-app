<?php

declare(strict_types=1);

use MusicRating\Controllers\UserController;
use MusicRating\Controllers\WebController;
use MusicRating\lib\Controller\BaseController;
use MusicRating\Middlewares\APIMiddleware;

ini_set('display_errors', 'on');
error_reporting(E_ALL);

include_once dirname(__DIR__, 1) . '/src/autoload.php';

$app = new BaseController();

APIMiddleware::apply();

//WEB --------------------------------------------------->
$app->get('/', WebController::home());

//API --------------------------------------------------->
$app->get('/v1/users', UserController::getAll());

$app->post('/v1/users', UserController::newUser());



// $app->post('/', function($req, $res, $body, $params) {
//     $body = json_decode($body);

//     echo $body->key;
// });

// $app->put('/', function($req, $res, $body) {
//     echo 'put home';
// });

// $app->delete('/', function($req, $res, $body) {
//     echo "$body";
// });

$app->dispatch();

if ($app->error()) {
    echo $app->error()->getMessage();
}
