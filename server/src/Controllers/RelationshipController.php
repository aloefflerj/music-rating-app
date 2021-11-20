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
            $songs = self::$relationships->getAllSongsFromAlbum($param->album);

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

    public static function getAll()
    {
        return function ($req, $res, $body) {
            $relationships = self::$relationships->getAll();

            if (self::$relationships->error()) {
                self::printError();
                return;
            }

            echo json_encode($relationships, JSON_PRETTY_PRINT);
        };
    }

    public static function get()
    {
        return function ($req, $res, $params) {
            $relationship = self::$relationships->get($params->id);

            if (self::$relationships->error()) {
                self::printError();
                return;
            }

            echo json_encode($relationship, JSON_PRETTY_PRINT);
        };
    }

    public static function new()
    {
        return function ($req, $res, $body) {
            $body = json_decode($body);

            $relationships = self::$relationships->new(
                $body->title ?? null
            );

            if (self::$relationships->error()) {
                self::printError();
                return;
            }

            echo json_encode($relationships, JSON_PRETTY_PRINT);
        };
    }

    public static function delete()
    {
        return function ($req, $res, $body, $param) {

            $relationships = self::$relationships->delete($param->id);

            if (self::$relationships->error()) {
                self::printError();
                return;
            }

            echo json_encode($relationships, JSON_PRETTY_PRINT);
        };
    }

    public static function update() 
    {
        return function ($req, $res, $body, $param) {

            $body = json_decode($body);

            $relationship = self::$relationships->update($param->id, $body);

            if (self::$relationships->error()) {
                self::printError();
                return;
            }

            echo json_encode($relationship, JSON_PRETTY_PRINT);
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
