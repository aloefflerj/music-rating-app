<?php

namespace MusicRating\Models;

use MusicRating\Models\Helpers\DBConnection;

class UsersModel
{
    use DBConnection;

    private $pdo;

    private $error;

    public function __construct()
    {
        $this->pdo = $this->conn('music_rating_app', 'music_rating_db', 'root', '123#@!');
    }

    /**
     *
     * @return void
     */
    public function getAllUsers()
    {

        $query = $this->pdo->prepare('SELECT * FROM users');
        $query->execute();

        $users = $query->fetchAll();

        return $users;
    }

    public function newUser(?string $username, ?string $mail, ?string $passwd, ?string $user_type)
    {

        $validatedPasswd = $this->validatePasswd($passwd); 

        if(!$validatedPasswd) {
            return;
        }

        $params = [
            'username' => $username,
            'mail' => $mail,
            'passwd' => $passwd,
            'user_type' => $user_type ?? 'regular'
        ];

        $query = $this->pdo->prepare(
            "INSERT INTO users (username, mail, passwd, user_type) VALUES (:username, :mail, :passwd, :user_type)"
        );

        try {
            $query->execute($params);
        } catch (\Exception $e) {
            $this->error = $e;
        }

        return $this->getAllUsers();
    }

    // fields validations ----------------------------------->
    public function validatePasswd($passwd) {
        
        if(empty($passwd)) {
            $this->error = new \Exception('A senha está vazia');
            return false;
        }
        
        if(!preg_match('/^(?=(.*[a-z]){3,})(?=(.*[A-Z]){2,})(?=(.*[0-9]){2,})(?=(.*[!@#$%^&*()\-__+.]){1,}).{8,}$/', $passwd)) {
            $this->error = new \Exception('A senha não atende aos requisitos mínimos');
            return false;
        }

        return true;
    }

    // helpers ------------------------------>
    public function error() {
        return $this->error ?? false;
    }
}
