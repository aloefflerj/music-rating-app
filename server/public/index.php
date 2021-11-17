<?php

declare(strict_types=1);

use MusicRating\Controllers\WebController;
use MusicRating\lib\Controller\BaseController;

ini_set('display_errors', 'on');
error_reporting(E_ALL);

include_once dirname(__DIR__, 1) . '/src/autoload.php';

$app = new BaseController();

$app->get('/', WebController::home());

$app->get('/v1/users', function ($req, $res, $params) {
    echo 'all users';
});

$app->dispatch();

if ($app->error()) {
    echo $app->error()->getMessage();
}
