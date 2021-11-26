<?php

namespace MusicRating\Models\Helpers;

trait UsersConnConfig
{
    public function getUserConn($root = false) 
    {
        $sessionUser = isset($_SESSION['user']) ? $_SESSION['user'] : false;

        $user = new \stdClass();  
        if(!isset($_SESSION['user'])) {
            $user = $this->createUserConn('music_rating_app', 'music_rating_db', 'web', '123!@#qweQWE', 'web_');
        }

        if($sessionUser && $sessionUser->user_type === 'app') {
            $user = $this->createUserConn('music_rating_app', 'music_rating_db', 'app', '123!@#qweQWE', 'app_');
        }

        if($sessionUser && $sessionUser->user_type === 'adm') {
            $user = $this->createUserConn('music_rating_app', 'music_rating_db', 'adm', '123!@#qweQWE', 'adm_');
        }

        if($root || $sessionUser->user_type === 'dba') {
            $user = $this->createUserConn('music_rating_app', 'music_rating_db', 'root', '123#@!', null);
        }


        return $user;
    }

    private function createUserConn($db, $host, $dbUser, $passwd, $tablesPrefix)
    {
        $user = new \stdClass();

        $user->db = $db;
        $user->host = $host;
        $user->user = $dbUser;
        $user->passwd = $passwd;
        $user->tablesPrefix = $tablesPrefix;

        return $user;
    }
    
}