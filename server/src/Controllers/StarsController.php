<?php

namespace MusicRating\Controllers;

use MusicRating\Models\StarsModel;

class StarsController
{

    /**
     * Undocumented variable
     *
     * @var StarsModel
     */
    public static $stars;

    public static function init()
    {
        /** @var $this->stars */
        self::$stars = new StarsModel();
    }

    public static function getAllStarredSongs()
    {
        return function ($req, $res, $body) {
            $stars = self::$stars->getAllStarredSongs();

            if (self::$stars->error()) {
                self::printError();
                return;
            }

            echo json_encode($stars, JSON_PRETTY_PRINT);
        };
    }

    public static function getStarredSong()
    {
        return function ($req, $res, $params) {
            $user = self::$stars->getStarredSong($params->id);

            if (self::$stars->error()) {
                self::printError();
                return;
            }

            echo json_encode($user, JSON_PRETTY_PRINT);
        };
    }

    public static function starASong()
    {
        return function ($req, $res, $body) {

            $body = json_decode($body);

            $stars = self::$stars->starASong(
                $body->stars ?? null,
                $body->song ?? null
            );

            if (self::$stars->error()) {
                self::printError();
                return;
            }

            echo json_encode($stars, JSON_PRETTY_PRINT);
        };
    }

    public static function delete()
    {
        return function ($req, $res, $body, $param) {

            $stars = self::$stars->getAllStarredSongs($param->id);

            if (self::$stars->error()) {
                self::printError();
                return;
            }

            echo json_encode($stars, JSON_PRETTY_PRINT);
        };
    }

    public static function update() 
    {
        return function ($req, $res, $body, $param) {

            $body = json_decode($body);

            $user = self::$stars->getAllStarredSongs($param->id, $body);

            if (self::$stars->error()) {
                self::printError();
                return;
            }

            echo json_encode($user, JSON_PRETTY_PRINT);
        };
    }

    private static function printError()
    {
        echo json_encode([
            'success' => false,
            'msg' => self::$stars->error()->getMessage()
        ], JSON_PRETTY_PRINT);
    }
}
