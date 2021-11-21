<?php

namespace MusicRating\Controllers;

use MusicRating\Models\UsersModel;

class AuthController
{

    /**
     * Undocumented variable
     *
     * @var UsersModel
     */
    public static $users;

    public static $userController;

    public static function init()
    {
        /** @var self::$artists */
        self::$users = new UsersModel();
    }

    public static function register()
    {
        return function ($req, $res, $body) {
            $body = json_decode($body);

            $users = self::$users->register(
                $body->username ?? null,
                $body->mail ?? null,
                $body->passwd ?? null,
                $body->passwdConfirm ?? null,
                $body->user_type ?? null
            );

            if (self::$users->error()) {
                self::printError();
                return;
            }

            echo json_encode($users, JSON_PRETTY_PRINT);
        };
    }

    public static function login()
    {
        return function($req, $res, $body) {
            $body = json_decode($body);

            $user = self::$users->login(
                $body->username ?? null,
                $body->passwd ?? null
            );

            if (self::$users->error()) {
                self::printError();
                return;
            }

            echo json_encode($user, JSON_PRETTY_PRINT);
        };
    }

    public static function logged()
    {
        return function($req, $res) {

            $logged = self::$users->logged();

            if (self::$users->error()) {
                self::printError();
                return;
            }

            echo json_encode($logged, JSON_PRETTY_PRINT);
        };
    }

    public static function logout()
    {
        return function($req, $res) {

            $logout = self::$users->logout();

            if (self::$users->error()) {
                self::printError();
                return;
            }

            echo json_encode($logout, JSON_PRETTY_PRINT);
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
