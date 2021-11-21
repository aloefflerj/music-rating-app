<?php

declare(strict_types=1);

use MusicRating\Controllers\AlbumController;
use MusicRating\Controllers\AppController;
use MusicRating\Controllers\ArtistController;
use MusicRating\Controllers\RelationshipController;
use MusicRating\Controllers\SongController;
use MusicRating\Controllers\UserController;
use MusicRating\Controllers\WebController;
use MusicRating\lib\Controller\BaseController;
use MusicRating\Middlewares\APIMiddleware;

ini_set('display_errors', 'on');
error_reporting(E_ALL);

include_once dirname(__DIR__, 1) . '/src/autoload.php';

$app = new BaseController();

// APIMiddleware::apply();

/**
 * =====================================
 * ||              WEB               || ==================================>
 * ====================================
 */
AppController::init();

WebController::init();
if(isset($_SESSION['user']['adm'])) {
    $app->get('/', WebController::home());
} else {
    $app->get('/', AppController::home());
    $app->get('/login', AppController::login());
}


/**
 * =====================================
 * ||              API               || ==================================>
 * ====================================
 */
// Relationships init ---------------->
RelationshipController::init();

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
$app->delete('/v1/songs/{id}', SongController::delete());
$app->put('/v1/songs/{id}', SongController::update());
$app->get('/v1/songs/filter/album/{id}', RelationshipController::getAllSongsFromAlbum());
$app->get('/v1/songs/filter/artist/{id}', RelationshipController::getAllSongsFromArtist());

// Album group ---------------->
AlbumController::init();
$app->get('/v1/albums', AlbumController::getAll());
$app->post('/v1/albums', AlbumController::new());
$app->get('/v1/albums/{id}', AlbumController::get());
$app->delete('/v1/albums/{id}', AlbumController::delete());
$app->put('/v1/albums/{id}', AlbumController::update());
$app->get('/v1/albums/filter/artist/{id}', RelationshipController::getAllAlbumsFromArtist());

// Artists group ---------------->
ArtistController::init();
$app->get('/v1/artists', ArtistController::getAll());
$app->post('/v1/artists', ArtistController::new());
$app->get('/v1/artists/{id}', ArtistController::get());
$app->delete('/v1/artists/{id}', ArtistController::delete());
$app->put('/v1/artists/{id}', ArtistController::update());


// Relationships group
$app->post('/v1/relationships/albums/addSong', RelationshipController::addSongToAlbum());
$app->post('/v1/relationships/artists/addSong', RelationshipController::addSongToArtist());
$app->post('/v1/relationships/artists/addAlbum', RelationshipController::addAlbumToArtist());


// Test group ---------------->
$app->get('/test/{id}', function ($req, $res, $params) {
    echo $params->id;
});

$app->dispatch();

// if ($app->error()) {
//     echo $app->error()->getMessage();
// }
