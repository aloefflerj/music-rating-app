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

    public static function home()
    {
        return function($req, $res, $params) {
            echo 'home';
        };
    }

}