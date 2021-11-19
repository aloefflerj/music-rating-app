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

    public static $curl;

    public static function init()
    {
        /** @var CoreView */
        self::$view = new CoreView();
        
    }
    
    public static function home()
    {
        // header('Content-Type: text/html');
        return function($req, $res, $params) {

            $cURLConnection = curl_init();

            curl_setopt($cURLConnection, CURLOPT_CONNECTTIMEOUT, 0); 
            curl_setopt($cURLConnection, CURLOPT_TIMEOUT, 10); //timeout in seconds
            curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($cURLConnection, CURLOPT_URL, 'http://localhost/v1/users');
            // curl_setopt($cURLConnection, CURLOPT_PORT, 8000);
            curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array('Accept: application/json'));
            curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

            $users = curl_exec($cURLConnection);
            curl_close($cURLConnection);

            $users = json_decode($users);

            $usersSection = '';
            foreach($users as $user) {
                $usersSection .= <<<USERS_SECTION
                    <h5>#{$user->id} Usuario: {$user->username} Email: {$user->mail}</h5>
                    
                USERS_SECTION;
            }

           
            echo self::$view->render('home', ['users' => $usersSection]);
        };
    }


}