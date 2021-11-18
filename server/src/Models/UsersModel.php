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
     * Retorna todos os usuários do banco
     *
     * @return stdClass[] | null
     */
    public function getAll(): ?array
    {
        $query = $this->pdo->prepare('SELECT * FROM users');

        try {
            $query->execute();
        } catch (\Exception $e) {
            $this->error = $e;
        }

        $users = $query->fetchAll();

        return $users;
    }

    public function get(int $id)
    {
        $query = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');

        try {
            $query->execute(['id' => $id]);
        } catch (\Exception $e) {
            $this->error = $e;
        }

        $user = $query->fetch();

        return $user;
    }

    /**
     * Cria um novo usuário
     *
     * @param string|null $username
     * @param string|null $mail
     * @param string|null $passwd
     * @param string|null $user_type
     * @return stdClass[]|null
     */
    public function new(?string $username, ?string $mail, ?string $passwd, ?string $user_type): ?array
    {
        $validatedPasswd = $this->validatePasswd($passwd);
        if (!$validatedPasswd) {
            return null;
        }

        $validatedUsername = $this->validateUsername($username);
        if (!$validatedUsername) {
            return null;
        }

        $validatedMail = $this->validateMail($mail);
        if (!$validatedMail) {
            return null;
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


        return $this->getAll();
    }

    public function delete ($id)
    {
        $users = $this->getAll();

        // user not found ------->
        $found = false;
        foreach ($users as $user) {
            if($user->id === $id) {
                $found = true;
            }
        }

        if (!$found) {
            $this->error = new \Exception('Este usuário não existe');
        }

        $validatedId = $this->validateId($id);
        if(!$validatedId) {
            return null;
        }

        $query = $this->pdo->prepare(
            "DELETE FROM users WHERE id = :id"
        );

        try {
            $query->execute(['id' => $id]);
        } catch (\Exception $e) {
            $this->error = $e;
        }

        return $this->getAll();
    }

    /*
     * =======================
     * || FIELD VALIDATIONS || =====================================>
     * =======================
     */

    // id
    private function validateId(&$id)
    {
        if (empty($id) || !filter_var($id, FILTER_VALIDATE_INT)) {
            $this->error = new \Exception('Insira uma id válido');
            return false;
        }

        return true;
    }

    // password ---------------------------------------------->
    private function validatePasswd(?string &$passwd)
    {
        if (empty($passwd)) {
            $this->error = new \Exception('Insira uma senha');
            return false;
        }

        if (!preg_match('/^(?=(.*[a-z]){3,})(?=(.*[A-Z]){2,})(?=(.*[0-9]){2,})(?=(.*[!@#$%^&*()\-__+.]){1,}).{8,}$/', $passwd)) {
            $this->error = new \Exception('A senha não atende aos requisitos mínimos');
            return false;
        }

        $passwd = password_hash($passwd, PASSWORD_DEFAULT);

        return true;
    }

    // username
    private function validateUsername(?string &$username)
    {

        if (empty($username)) {
            $this->error = new \Exception('Insira um nome de usuário');
            return false;
        }

        $users = $this->getAll();

        foreach ($users as $user) {
            if ($user->username === $username) {
                $this->error = new \Exception('Este nome de usuário já está sendo usado');
                return false;
            }
        }

        return true;
    }

    // mail
    private function validateMail(?string &$mail)
    {
        if (empty($mail)) {
            $this->error = new \Exception('Insira um email');
            return false;
        }

        if (!preg_match(
            '/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/',
            $mail
        )) {
            $this->error = new \Exception('Insira um email válido');
            return false;
        }

        $users = $this->getAll();

        foreach ($users as $user) {
            if ($user->mail === $mail) {
                $this->error = new \Exception('Este email já está cadastrado em nosso sitema');
                return false;
            }
        }

        return true;
    }

    /*
     * =============
     * || HELPERS || =============================================>
     * =============
     */
    public function error()
    {
        return $this->error ?? false;
    }
}
