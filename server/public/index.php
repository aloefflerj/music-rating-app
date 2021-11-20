<?php

declare(strict_types=1);

use MusicRating\Controllers\ArtistController;
use MusicRating\Controllers\SongController;
use MusicRating\Controllers\UserController;
use MusicRating\Controllers\WebController;
use MusicRating\lib\Controller\BaseController;
use MusicRating\Middlewares\APIMiddleware;

ini_set('display_errors', 'on');
error_reporting(E_ALL);

include_once dirname(__DIR__, 1) . '/src/autoload.php';

$app = new BaseController();

APIMiddleware::apply();

/**
 * =====================================
 * ||              WEB               || ==================================>
 * ====================================
 */
WebController::init();
$app->get('/', WebController::home());

/**
 * =====================================
 * ||              API               || ==================================>
 * ====================================
 */

// Users group ----------------->
UserController::init();
$app->get('/v1/users', UserController::getAll());
$app->post('/v1/users', UserController::new());
$app->get('/v1/users/{id}', UserController::get());
$app->delete('/v1/users/{id}', UserController::delete());
$app->put('/v1/users/{id}', UserController::update());

// Songs group ---------------->
SongController::init();
$app->get('/v1/songs', SongController::getAll());
$app->post('/v1/songs', SongController::new());
$app->get('/v1/songs/{id}', SongController::get());

// Artists group ---------------->
ArtistController::init();
$app->get('/v1/artists', ArtistController::getAll());
$app->post('/v1/artists', ArtistController::new());
$app->get('/v1/artists/{id}', ArtistController::get());
$app->delete('/v1/artists/{id}', ArtistController::delete());
$app->put('/v1/artists/{id}', ArtistController::update());


// Test group ---------------->
$app->get('/test/{id}', function ($req, $res, $params) {
    echo $params->id;
});

$app->dispatch();

if ($app->error()) {
    echo $app->error()->getMessage();
}
