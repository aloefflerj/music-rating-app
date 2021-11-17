<?php

namespace MusicRating\Controllers;

class WebController
{
    public static function home()
    {
        return function($req, $res, $params) {
            echo 'home';
        };
    }
}