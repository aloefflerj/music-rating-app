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
            $users = self::$users->getAllUsers();

            echo json_encode($users);
        };
    }

    public static function get()
    {
        return function ($req, $res, $params) {
            $user = self::$users->get($params->id);

            echo json_encode($user);
        };
    }

    public static function newUser()
    {
        return function ($req, $res, $body) {

            $body = json_decode($body);

            $users = self::$users->newUser(
                $body->username ?? null,
                $body->mail ?? null,
                $body->passwd ?? null,
                $body->user_type ?? null
            );

            if(self::$users->error()) {
                
                echo json_encode([
                    "success" => false,
                    "msg" => self::$users->error()->getMessage()
                ]);

                return;
            }

            echo json_encode($users);
        };
    }

    // errors ------------------------------->

    private static function checkEmptyBodyParams($body) {

    }
    
}
