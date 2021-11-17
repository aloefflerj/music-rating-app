<?php

namespace MusicRating\Controllers;

use MusicRating\Models\UsersModel;

class WebController
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

    public static function home()
    {
        return function($req, $res, $params) {
            echo 'home';
        };
    }

    public static function users() 
    {
        return function($req, $res, $body) {
            (new UsersModel)->getAllUsers();
        };
    }
}