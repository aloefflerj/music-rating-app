<?php

namespace MusicRating\Controllers;

use MusicRating\Models\RelationshipsModel;

class RelationshipController
{

    /**
     * Undocumented variable
     *
     * @var relationshipsModel
     */
    public static $relationships;

    public static function init()
    {
        /** @var self::$relationships */
        self::$relationships = new RelationshipsModel();
    }

    public static function getAllSongsFromAlbum()
    {
        return function ($req, $res, $param) {
            $songs = self::$relationships->getAllSongsFromAlbum($param->id);

            if (self::$relationships->error()) {
                self::printError();
                return;
            }

            echo json_encode($songs, JSON_PRETTY_PRINT);
        };
    }

    public static function getAllSongsFromArtist()
    {
        return function ($req, $res, $param) {
            $songs = self::$relationships->getAllSongsFromArtist($param->id);

            if (self::$relationships->error()) {
                self::printError();
                return;
            }

            echo json_encode($songs, JSON_PRETTY_PRINT);
        };
    }

    public static function getAllAlbumsFromArtist()
    {
        return function ($req, $res, $param) {
            $songs = self::$relationships->getAllAlbumsFromArtist($param->id);

            if (self::$relationships->error()) {
                self::printError();
                return;
            }

            echo json_encode($songs, JSON_PRETTY_PRINT);
        };
    }

    public static function addSongToAlbum()
    {
        return function ($req, $res, $body) {
            $body = json_decode($body);

            $album = self::$relationships->addSongToAlbum((int)$body->songId, (int)$body->albumId);

            if (self::$relationships->error()) {
                self::printError();
                return;
            }

            echo json_encode($album, JSON_PRETTY_PRINT);
        };
    }

    public static function addSongToArtist()
    {
        return function ($req, $res, $body) {
            $body = json_decode($body);

            $artist = self::$relationships->addSongToArtist((int)$body->songId, (int)$body->artistId);

            if (self::$relationships->error()) {
                self::printError();
                return;
            }

            echo json_encode($artist, JSON_PRETTY_PRINT);
        };
    }

    public static function addAlbumToArtist()
    {
        return function ($req, $res, $body) {
            $body = json_decode($body);

            $artist = self::$relationships->addAlbumToArtist((int)$body->albumId, (int)$body->artistId);

            if (self::$relationships->error()) {
                self::printError();
                return;
            }

            echo json_encode($artist, JSON_PRETTY_PRINT);
        };
    }


    private static function printError()
    {
        echo json_encode([
            'success' => false,
            'msg' => self::$relationships->error()->getMessage()
        ], JSON_PRETTY_PRINT);
    }
}
