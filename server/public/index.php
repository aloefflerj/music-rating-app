<?php

declare(strict_types=1);

use MusicRating\Controllers\WebController;
use MusicRating\lib\Controller\BaseController;

ini_set('display_errors', 'on');
error_reporting(E_ALL);

include_once dirname(__DIR__, 1) . '/src/autoload.php';

// $dsn = 'mysql:host=music_rating_db;dbname=music_rating_app;port=3306';
// $user = 'root';
// $password = '123#@!';

// try {
//     $dbh = new \PDO($dsn, $user, $password);
// } catch (\PDOException $e) {
//     die("Database connection failed: {$e->getMessage()}");
// }


$app = new BaseController();

$app->get('/', WebController::home());

$app->get('/v1/users', WebController::users());

$app->post('/', function($req, $res, $body, $params) {
    $body = json_decode($body);

    echo $body->key;
});

$app->put('/', function($req, $res, $body) {
    echo 'put home';
});

$app->delete('/', function($req, $res, $body) {
    echo "$body";
});

$app->dispatch();

if ($app->error()) {
    echo $app->error()->getMessage();
}
