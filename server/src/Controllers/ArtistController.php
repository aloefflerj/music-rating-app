<?php

namespace MusicRating\Controllers;

use MusicRating\Models\ArtistsModel;

class ArtistController
{

    /**
     * Undocumented variable
     *
     * @var ArtistsModel
     */
    public static $artists;

    public static function init()
    {
        /** @var self::$artists */
        self::$artists = new ArtistsModel();
    }

    public static function getAll()
    {
        return function ($req, $res, $body) {
            $artists = self::$artists->getAll();

            if (self::$artists->error()) {
                self::printError();
                return;
            }

            echo json_encode($artists, JSON_PRETTY_PRINT);
        };
    }

    public static function get()
    {
        return function ($req, $res, $params) {
            $artist = self::$artists->get($params->id);

            if (self::$artists->error()) {
                self::printError();
                return;
            }

            echo json_encode($artist, JSON_PRETTY_PRINT);
        };
    }

    public static function new()
    {
        return function ($req, $res, $body) {
            $body = json_decode($body);

            $artists = self::$artists->new(
                $body->name ?? null
            );

            if (self::$artists->error()) {
                self::printError();
                return;
            }

            echo json_encode($artists, JSON_PRETTY_PRINT);
        };
    }

    public static function delete()
    {
        return function ($req, $res, $body, $param) {

            $artists = self::$artists->delete($param->id);

            if (self::$artists->error()) {
                self::printError();
                return;
            }

            echo json_encode($artists, JSON_PRETTY_PRINT);
        };
    }

    public static function update() 
    {
        return function ($req, $res, $body, $param) {

            $body = json_decode($body);

            $artist = self::$artists->update($param->id, $body);

            if (self::$artists->error()) {
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
            'msg' => self::$artists->error()->getMessage()
        ], JSON_PRETTY_PRINT);
    }
}
