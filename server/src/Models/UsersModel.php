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

        $query = $this->pdo->prepare('SELECT * FROM users');
        $query->execute();

        $users = $query->fetchAll();

        return $users;

    }

    public function newUser($username, $mail, $passwd, $user_type) {

        $params = [
            'username' => $username,
            'mail' => $mail,
            'passwd' => $passwd,
            'user_type' => $user_type
        ];
        
        $query = $this->pdo->prepare(
            "INSERT INTO users (username, mail, passwd, user_type) VALUES (:username, :mail, :passwd, :user_type)"
        );

        try {
            $query->execute($params);
        } catch (\Exception $e) {
            throw $e;
        }

        return $this->getAllUsers();
    }
}