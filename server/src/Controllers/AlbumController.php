<?php

namespace MusicRating\Controllers;

use MusicRating\Models\AlbumsModel;

class AlbumController
{

    /**
     * Undocumented variable
     *
     * @var AlbumsModel
     */
    public static $albums;

    public static function init()
    {
        /** @var self::$albums */
        self::$albums = new AlbumsModel();
    }

    public static function getAll()
    {
        return function ($req, $res, $body) {
            $albums = self::$albums->getAll();

            if (self::$albums->error()) {
                self::printError();
                return;
            }

            echo json_encode($albums, JSON_PRETTY_PRINT);
        };
    }

    public static function get()
    {
        return function ($req, $res, $params) {
            $album = self::$albums->get($params->id);

            if (self::$albums->error()) {
                self::printError();
                return;
            }

            echo json_encode($album, JSON_PRETTY_PRINT);
        };
    }

    public static function new()
    {
        return function ($req, $res, $body) {
            $body = json_decode($body);

            $albums = self::$albums->new(
                $body->title ?? null
            );

            if (self::$albums->error()) {
                self::printError();
                return;
            }

            echo json_encode($albums, JSON_PRETTY_PRINT);
        };
    }

    public static function delete()
    {
        return function ($req, $res, $body, $param) {

            $albums = self::$albums->delete($param->id);

            if (self::$albums->error()) {
                self::printError();
                return;
            }

            echo json_encode($albums, JSON_PRETTY_PRINT);
        };
    }

    public static function update() 
    {
        return function ($req, $res, $body, $param) {

            $body = json_decode($body);

            $album = self::$albums->update($param->id, $body);

            if (self::$albums->error()) {
                self::printError();
                return;
            }

            echo json_encode($album, JSON_PRETTY_PRINT);
        };
    }

    private static function printError()
    {
        echo json_encode([
            'success' => false,
            'msg' => self::$albums->error()->getMessage()
        ], JSON_PRETTY_PRINT);
    }
}
