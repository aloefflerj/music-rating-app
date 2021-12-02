<?php

namespace MusicRating\Controllers;

use MusicRating\Models\UsersModel;
use MusicRating\Views\CoreView;

class WebController
{
    
    public static function home()
    {
        // header('Content-Type: text/html');
        return function($req, $res, $params) {

            echo json_encode("Music Rating App ~ API");
        };
    }


}