<?php

namespace MusicRating\Controllers;

use MusicRating\Models\UsersModel;

class UserController
{

    /**
     * Undocumented variable
     *
     * @var UsersModel
     */
    public static $users;

    public static function init()
    {
        /** @var $this->users */
        self::$users = new UsersModel();
    }

    public static function getAll()
    {
        return function ($req, $res, $body) {
            $users = self::$users->getAll();

            if (self::$users->error()) {
                self::printError();
                return;
            }

            echo json_encode($users, JSON_PRETTY_PRINT);
        };
    }

    public static function get()
    {
        return function ($req, $res, $params) {
            $user = self::$users->get($params->id);

            if (self::$users->error()) {
                self::printError();
                return;
            }

            echo json_encode($user, JSON_PRETTY_PRINT);
        };
    }

    public static function new()
    {
        return function ($req, $res, $body) {

            $body = json_decode($body);

            $users = self::$users->new(
                $body->username ?? null,
                $body->mail ?? null,
                $body->passwd ?? null,
                $body->user_type ?? null
            );

            if (self::$users->error()) {
                self::printError();
                return;
            }

            echo json_encode($users, JSON_PRETTY_PRINT);
        };
    }

    public static function delete()
    {
        return function ($req, $res, $body, $param) {

            $users = self::$users->delete($param->id);

            if (self::$users->error()) {
                self::printError();
                return;
            }

            echo json_encode($users, JSON_PRETTY_PRINT);
        };
    }

    private static function printError()
    {
        echo json_encode([
            'success' => false,
            'msg' => self::$users->error()->getMessage()
        ], JSON_PRETTY_PRINT);
    }
}
