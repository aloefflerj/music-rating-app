<?php

namespace MusicRating\Controllers;

use MusicRating\Models\SongsModel;

class SongController
{

    /**
     * Undocumented variable
     *
     * @var SongsModel
     */
    public static $songs;

    public static function init()
    {
        /** @var self::$songs */
        self::$songs = new SongsModel();
    }

    public static function getAll()
    {
        return function ($req, $res, $body) {
            $songs = self::$songs->getAll();

            if (self::$songs->error()) {
                self::printError();
                return;
            }

            echo json_encode($songs, JSON_PRETTY_PRINT);
        };
    }

    public static function get()
    {
        return function ($req, $res, $params) {
            $song = self::$songs->get($params->id);

            if (self::$songs->error()) {
                self::printError();
                return;
            }

            echo json_encode($song, JSON_PRETTY_PRINT);
        };
    }

    public static function new()
    {
        return function ($req, $res, $body) {
            $body = json_decode($body);

            $songs = self::$songs->new(
                $body->title ?? null,
                $body->song_order ?? null,
            );

            if (self::$songs->error()) {
                self::printError();
                return;
            }

            echo json_encode($songs, JSON_PRETTY_PRINT);
        };
    }

    public static function delete()
    {
        return function ($req, $res, $body, $param) {

            $songs = self::$songs->delete($param->id);

            if (self::$songs->error()) {
                self::printError();
                return;
            }

            echo json_encode($songs, JSON_PRETTY_PRINT);
        };
    }

    public static function update() 
    {
        return function ($req, $res, $body, $param) {

            $body = json_decode($body);

            $song = self::$songs->update($param->id, $body);

            if (self::$songs->error()) {
                self::printError();
                return;
            }

            echo json_encode($song, JSON_PRETTY_PRINT);
        };
    }

    private static function printError()
    {
        echo json_encode([
            'success' => false,
            'msg' => self::$songs->error()->getMessage()
        ], JSON_PRETTY_PRINT);
    }
}
