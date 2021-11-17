<?php

namespace MusicRating\Models;

use MusicRating\Models\Helpers\DBConnection;

class UsersModel
{
    use DBConnection;

    private $pdo;

    public function __construct() {
        $this->pdo = $this->conn('music_rating_app', 'music_rating_db', 'root', '123#@!');
    }

    /**
     *
     * @return void
     */
    public function getAllUsers() {

        $users = $this->pdo->query('SELECT * FROM users');

        foreach($users as $user) {
            echo "User: {$user->username}, Id: {$user->id}, Type: {$user->type} <br>";
        }

    }
}