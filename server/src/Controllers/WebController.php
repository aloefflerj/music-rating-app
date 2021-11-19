<?php

namespace MusicRating\Controllers;

use MusicRating\Models\UsersModel;
use MusicRating\Views\CoreView;

class WebController
{

    /**
     * Undocumented variable
     *
     * @var CoreView
     */
    public static $view;

    public static function init()
    {
        /** @var CoreView */
        self::$view = new CoreView();
    }
    
    public static function home()
    {
        header('Content-Type: text/html');
        return function($req, $res, $params) {
            // echo '<h1>oi</h1>';
            // echo json_encode('welcome', JSON_PRETTY_PRINT);
            echo self::$view->render('home', ['greeting' => 'olá', 'footer' => 'aqui vem o footer']);
        };
    }

}