<?php

declare(strict_types=1);

use MusicRating\Controller\BaseController;

ini_set('display_errors', 'on');
error_reporting(E_ALL);

include_once dirname(__DIR__, 1) . '/src/autoload.php';

$app = new BaseController();

$app->get('/', function ($req, $res, $params) {
    echo 'hello worldzito';
});

$app->dispatch();

if ($app->error()) {
    echo $app->error()->getMessage();
}
