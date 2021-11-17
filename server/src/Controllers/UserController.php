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

    public function __construct() {
        
        /** @var $this->users */
        self::$users = new UsersModel();
    }

    public static function getAll() 
    {
        return function($req, $res, $body) {
            $users = (new UsersModel())->getAllUsers();

            echo json_encode($users);
        };
    }

    public static function newUser() {
        return function($req, $res, $body) {

            $body = json_decode($body);
            
            $users = (new UsersModel())->newUser(
                $body->username,
                $body->mail,
                $body->passwd,
                $body->user_type
            );

            echo json_encode($users);
        };
    }
}